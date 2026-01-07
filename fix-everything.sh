#!/bin/bash
# fix-everything.sh

echo "=========================================="
echo "FIXING EVERYTHING FOR AKAUNTING DEPLOYMENT"
echo "=========================================="

echo ""
echo "=== Step 1: Remove duplicate files ==="
echo "Removing duplicate configmap.yaml..."
rm -f k8s/configmap.yaml

echo ""
echo "=== Step 2: Create clean k8s directory ==="
mkdir -p k8s

echo ""
echo "=== Step 3: Create all k8s files ==="

# 1. namespace.yaml
cat > k8s/namespace.yaml << 'EOF'
apiVersion: v1
kind: Namespace
metadata:
  name: akaunting
  labels:
    name: akaunting
EOF
echo "✓ Created namespace.yaml"

# 2. mysql-pvc.yaml
cat > k8s/mysql-pvc.yaml << 'EOF'
apiVersion: v1
kind: PersistentVolumeClaim
metadata:
  name: mysql-pvc
  namespace: akaunting
spec:
  accessModes:
    - ReadWriteOnce
  storageClassName: gp2
  resources:
    requests:
      storage: 10Gi
EOF
echo "✓ Created mysql-pvc.yaml"

# 3. mysql-deployment.yaml
cat > k8s/mysql-deployment.yaml << 'EOF'
apiVersion: apps/v1
kind: Deployment
metadata:
  name: mysql
  namespace: akaunting
spec:
  replicas: 1
  selector:
    matchLabels:
      app: mysql
  template:
    metadata:
      labels:
        app: mysql
    spec:
      containers:
        - name: mysql
          image: mysql:8.0
          env:
            - name: MYSQL_DATABASE
              value: "akaunting"
            - name: MYSQL_USER
              value: "akaunting"
            - name: MYSQL_PASSWORD
              value: "akauntingpass"
            - name: MYSQL_ROOT_PASSWORD
              value: "rootpassword"
          ports:
            - containerPort: 3306
          volumeMounts:
            - name: mysql-storage
              mountPath: /var/lib/mysql
          resources:
            requests:
              memory: "256Mi"
              cpu: "250m"
            limits:
              memory: "512Mi"
              cpu: "500m"
      volumes:
        - name: mysql-storage
          persistentVolumeClaim:
            claimName: mysql-pvc
EOF
echo "✓ Created mysql-deployment.yaml"

# 4. mysql-service.yaml
cat > k8s/mysql-service.yaml << 'EOF'
apiVersion: v1
kind: Service
metadata:
  name: mysql
  namespace: akaunting
spec:
  selector:
    app: mysql
  ports:
    - port: 3306
      targetPort: 3306
EOF
echo "✓ Created mysql-service.yaml"

# 5. app-configmap.yaml
cat > k8s/app-configmap.yaml << 'EOF'
apiVersion: v1
kind: ConfigMap
metadata:
  name: akaunting-config
  namespace: akaunting
data:
  APP_NAME: "Akaunting"
  APP_ENV: "production"
  APP_DEBUG: "false"
  APP_URL: "http://localhost"
  
  DB_CONNECTION: "mysql"
  DB_HOST: "mysql"
  DB_PORT: "3306"
  DB_DATABASE: "akaunting"
  DB_USERNAME: "akaunting"
  
  CACHE_DRIVER: "file"
  SESSION_DRIVER: "file"
  QUEUE_CONNECTION: "sync"
  
  LOG_CHANNEL: "stack"
  LOG_LEVEL: "error"
EOF
echo "✓ Created app-configmap.yaml"

# 6. app-secret.yaml
cat > k8s/app-secret.yaml << 'EOF'
apiVersion: v1
kind: Secret
metadata:
  name: akaunting-secret
  namespace: akaunting
type: Opaque
stringData:
  APP_KEY: "base64:B1DqwDxbX+b5a24tyy1WHl5jAI3v0qPhnKGuomBI+0Y="
  DB_PASSWORD: "akauntingpass"
EOF
echo "✓ Created app-secret.yaml"

# 7. deployment.yaml
cat > k8s/deployment.yaml << 'EOF'
apiVersion: apps/v1
kind: Deployment
metadata:
  name: akaunting
  namespace: akaunting
spec:
  replicas: 2
  selector:
    matchLabels:
      app: akaunting
  template:
    metadata:
      labels:
        app: akaunting
    spec:
      containers:
        - name: akaunting
          image: 147997152282.dkr.ecr.us-east-1.amazonaws.com/akaunting:latest
          imagePullPolicy: Always
          ports:
            - containerPort: 80
          envFrom:
            - configMapRef:
                name: akaunting-config
            - secretRef:
                name: akaunting-secret
          readinessProbe:
            httpGet:
              path: /
              port: 80
            initialDelaySeconds: 30
            periodSeconds: 10
            timeoutSeconds: 5
          livenessProbe:
            httpGet:
              path: /
              port: 80
            initialDelaySeconds: 60
            periodSeconds: 30
          resources:
            requests:
              memory: "256Mi"
              cpu: "100m"
            limits:
              memory: "512Mi"
              cpu: "500m"
EOF
echo "✓ Created deployment.yaml"

# 8. service.yaml
cat > k8s/service.yaml << 'EOF'
apiVersion: v1
kind: Service
metadata:
  name: akaunting-service
  namespace: akaunting
spec:
  type: LoadBalancer
  selector:
    app: akaunting
  ports:
    - port: 80
      targetPort: 80
      protocol: TCP
EOF
echo "✓ Created service.yaml"

# 9. ingress.yaml (optional)
cat > k8s/ingress.yaml << 'EOF'
apiVersion: networking.k8s.io/v1
kind: Ingress
metadata:
  name: akaunting-ingress
  namespace: akaunting
  annotations:
    kubernetes.io/ingress.class: "alb"
    alb.ingress.kubernetes.io/scheme: internet-facing
    alb.ingress.kubernetes.io/target-type: ip
    alb.ingress.kubernetes.io/listen-ports: '[{"HTTP": 80}]'
spec:
  rules:
    - http:
        paths:
          - path: /
            pathType: Prefix
            backend:
              service:
                name: akaunting-service
                port:
                  number: 80
EOF
echo "✓ Created ingress.yaml"

echo ""
echo "=== Step 4: Fix GitHub workflow ==="
mkdir -p .github/workflows

cat > .github/workflows/deploy.yml << 'EOF'
name: Deploy Akaunting to EKS

on:
  workflow_dispatch:

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - name: Checkout code
      uses: actions/checkout@v4
    
    - name: Configure AWS credentials
      uses: aws-actions/configure-aws-credentials@v4
      with:
        aws-access-key-id: ${{ secrets.AWS_ACCESS_KEY_ID }}
        aws-secret-access-key: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
        aws-region: us-east-1
    
    - name: Login to Amazon ECR
      uses: aws-actions/amazon-ecr-login@v2
    
    - name: Build Docker image
      run: |
        docker build -t akaunting-app .
        docker tag akaunting-app:latest ${{ secrets.AWS_ACCOUNT_ID }}.dkr.ecr.us-east-1.amazonaws.com/akaunting:latest
    
    - name: Push to ECR
      run: |
        docker push ${{ secrets.AWS_ACCOUNT_ID }}.dkr.ecr.us-east-1.amazonaws.com/akaunting:latest
    
    - name: Setup kubectl
      uses: aws-actions/amazon-eks-set-kubectl@v1
      with:
        version: 'v1.29.0'
    
    - name: Update kubeconfig
      run: |
        aws eks update-kubeconfig \
          --region us-east-1 \
          --name akaunting-devsecops2
    
    - name: Deploy to Kubernetes
      run: |
        # Update image in deployment
        sed -i "s|147997152282.dkr.ecr.us-east-1.amazonaws.com/akaunting:latest|${{ secrets.AWS_ACCOUNT_ID }}.dkr.ecr.us-east-1.amazonaws.com/akaunting:latest|g" k8s/deployment.yaml
        
        # Apply all manifests
        kubectl apply -f k8s/namespace.yaml
        kubectl apply -f k8s/mysql-pvc.yaml
        kubectl apply -f k8s/mysql-deployment.yaml
        kubectl apply -f k8s/mysql-service.yaml
        sleep 30
        kubectl apply -f k8s/app-configmap.yaml
        kubectl apply -f k8s/app-secret.yaml
        kubectl apply -f k8s/deployment.yaml
        kubectl apply -f k8s/service.yaml
        
        echo "Waiting for deployment..."
        sleep 60
        
        # Show status
        kubectl get pods -n akaunting
        kubectl get svc -n akaunting
        
        echo ""
        echo "LoadBalancer URL:"
        kubectl get svc akaunting-service -n akaunting -o jsonpath='{.status.loadBalancer.ingress[0].hostname}'
        echo ""
EOF
echo "✓ Created .github/workflows/deploy.yml"

echo ""
echo "=== Step 5: Create setup script ==="
cat > setup.sh << 'EOF'
#!/bin/bash
# setup.sh - Setup everything

echo "1. Make scripts executable:"
chmod +x fix-everything.sh
chmod +x manual-deploy.sh

echo ""
echo "2. Run fix script:"
./fix-everything.sh

echo ""
echo "3. Check your files:"
ls -la k8s/

echo ""
echo "4. ADD THESE GITHUB SECRETS:"
echo "   Go to: GitHub → Settings → Secrets and variables → Actions"
echo "   Add these:"
echo "   - AWS_ACCESS_KEY_ID (your AWS key)"
echo "   - AWS_SECRET_ACCESS_KEY (your AWS secret)"
echo "   - AWS_ACCOUNT_ID (147997152282)"
echo ""
echo "5. Push to GitHub:"
echo "   git add ."
echo "   git commit -m 'Fixed deployment'"
echo "   git push"
echo ""
echo "6. Run workflow:"
echo "   Go to GitHub → Actions → Deploy Akaunting to EKS → Run workflow"
EOF
chmod +x setup.sh
echo "✓ Created setup.sh"

echo ""
echo "=== Step 6: Create manual deploy script ==="
cat > manual-deploy.sh << 'EOF'
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
EOF
chmod +x manual-deploy.sh
echo "✓ Created manual-deploy.sh"

echo ""
echo "=========================================="
echo "✅ EVERYTHING FIXED!"
echo "=========================================="
echo ""
echo "NEXT STEPS:"
echo "1. Run: chmod +x fix-everything.sh"
echo "2. Run: chmod +x manual-deploy.sh"
echo "3. Run: ./setup.sh"
echo ""
echo "OR for quick manual deploy:"
echo "./manual-deploy.sh"
echo ""
