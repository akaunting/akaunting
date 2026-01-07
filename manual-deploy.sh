#!/bin/bash
# manual-deploy.sh - Deploy manually

echo "=== MANUAL DEPLOYMENT ==="

# Set variables
AWS_ACCOUNT_ID="147997152282"
AWS_REGION="us-east-1"

echo "1. Login to ECR..."
aws ecr get-login-password --region $AWS_REGION | docker login --username AWS --password-stdin $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com

echo "2. Build Docker image..."
docker build -t akaunting-app .

echo "3. Tag and push to ECR..."
docker tag akaunting-app:latest $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/akaunting:latest
docker push $AWS_ACCOUNT_ID.dkr.ecr.$AWS_REGION.amazonaws.com/akaunting:latest

echo "4. Update kubeconfig..."
aws eks update-kubeconfig --region $AWS_REGION --name akaunting-devsecops2

echo "5. Deploy to Kubernetes..."
kubectl apply -f k8s/namespace.yaml
kubectl apply -f k8s/mysql-pvc.yaml
kubectl apply -f k8s/mysql-deployment.yaml
kubectl apply -f k8s/mysql-service.yaml

echo "Waiting for MySQL (30 seconds)..."
sleep 30

kubectl apply -f k8s/app-configmap.yaml
kubectl apply -f k8s/app-secret.yaml
kubectl apply -f k8s/deployment.yaml
kubectl apply -f k8s/service.yaml

echo "Waiting for deployment (60 seconds)..."
sleep 60

echo ""
echo "=== DEPLOYMENT STATUS ==="
kubectl get pods -n akaunting
echo ""
kubectl get svc -n akaunting
echo ""
echo "LoadBalancer URL:"
kubectl get svc akaunting-service -n akaunting -o jsonpath='{.status.loadBalancer.ingress[0].hostname}'
echo ""
