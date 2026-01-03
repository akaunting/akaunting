name: DevSecOps Pipeline

on:
  push:
    branches: [ main, master, develop ]
  pull_request:
    branches: [ main, master ]
  workflow_dispatch:

env:
  AWS_REGION: us-east-1
  ECR_REPOSITORY: akaunting
  EKS_CLUSTER_NAME: akaunting-cluster
  KUBERNETES_NAMESPACE: akaunting

jobs:
  test:
    name: Run Tests
    runs-on: ubuntu-latest
    
    services:
      mysql:
        image: mysql:8.0
        env:
          MYSQL_ROOT_PASSWORD: root
          MYSQL_DATABASE: akaunting_test
        ports:
          - 3306:3306
        options: --health-cmd="mysqladmin ping" --health-interval=10s --health-timeout=5s --health-retries=3

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: bcmath, ctype, dom, fileinfo, intl, gd, json, mbstring, pdo, pdo_mysql, openssl, xml, zip

      - name: Test API endpoints
        run: |
          # Test simple de connexion à la base de données
          php -r "require 'api/db.php'; \$db = Database::connect(); echo 'DB connection OK';"
        env:
          DB_HOST: 127.0.0.1
          DB_DATABASE: akaunting_test
          DB_USERNAME: root
          DB_PASSWORD: root

  sast:
    name: Static Application Security Testing
    runs-on: ubuntu-latest
    
    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Run Semgrep SAST
        uses: returntocorp/semgrep-action@v1
        with:
          config: |
            p/php
            p/javascript
            p/security-audit
        continue-on-error: true

  sca:
    name: Software Composition Analysis
    runs-on: ubuntu-latest
    
    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Run Trivy vulnerability scanner
        uses: aquasecurity/trivy-action@master
        with:
          scan-type: 'fs'
          scan-ref: '.'
          format: 'sarif'
          output: 'trivy-results.sarif'
          severity: 'CRITICAL,HIGH'
        continue-on-error: true

      - name: Upload Trivy results to GitHub Security
        uses: github/codeql-action/upload-sarif@v3
        if: always()
        with:
          sarif_file: 'trivy-results.sarif'

  build-and-scan:
    name: Build Docker Image & Security Scan
    runs-on: ubuntu-latest
    needs: [test, sast, sca]
    
    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Set up Docker Buildx
        uses: docker/setup-buildx-action@v3

      - name: Configure AWS credentials
        uses: aws-actions/configure-aws-credentials@v4
        with:
          aws-access-key-id: ${{ secrets.AWS_ACCESS_KEY_ID }}
          aws-secret-access-key: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          aws-region: ${{ env.AWS_REGION }}

      - name: Login to Amazon ECR
        id: login-ecr
        uses: aws-actions/amazon-ecr-login@v2

      - name: Build Docker image
        uses: docker/build-push-action@v5
        with:
          context: .
          push: false
          tags: ${{ env.ECR_REPOSITORY }}:latest
          cache-from: type=gha
          cache-to: type=gha,mode=max

      - name: Scan Docker image with Trivy
        uses: aquasecurity/trivy-action@master
        with:
          image-ref: ${{ env.ECR_REPOSITORY }}:latest
          format: 'sarif'
          output: 'trivy-image-results.sarif'
          severity: 'CRITICAL,HIGH'
        continue-on-error: true

      - name: Upload Trivy image scan results
        uses: github/codeql-action/upload-sarif@v3
        if: always()
        with:
          sarif_file: 'trivy-image-results.sarif'

      - name: Tag and push image to ECR
        if: github.event_name == 'push'
        run: |
          docker tag ${{ env.ECR_REPOSITORY }}:latest ${{ steps.login-ecr.outputs.registry }}/${{ env.ECR_REPOSITORY }}:${{ github.sha }}
          docker tag ${{ env.ECR_REPOSITORY }}:latest ${{ steps.login-ecr.outputs.registry }}/${{ env.ECR_REPOSITORY }}:latest
          docker push ${{ steps.login-ecr.outputs.registry }}/${{ env.ECR_REPOSITORY }}:${{ github.sha }}
          docker push ${{ steps.login-ecr.outputs.registry }}/${{ env.ECR_REPOSITORY }}:latest

  deploy:
    name: Deploy to AWS EKS
    runs-on: ubuntu-latest
    needs: [build-and-scan]
    if: github.event_name == 'push' && (github.ref == 'refs/heads/main' || github.ref == 'refs/heads/master')
    environment: production
    
    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Configure AWS credentials
        uses: aws-actions/configure-aws-credentials@v4
        with:
          aws-access-key-id: ${{ secrets.AWS_ACCESS_KEY_ID }}
          aws-secret-access-key: ${{ secrets.AWS_SECRET_ACCESS_KEY }}
          aws-region: ${{ env.AWS_REGION }}

      - name: Install kubectl
        uses: azure/setup-kubectl@v3

      - name: Configure kubectl for EKS
        run: |
          aws eks update-kubeconfig --name ${{ env.EKS_CLUSTER_NAME }} --region ${{ env.AWS_REGION }}

      - name: Create namespace if not exists
        run: |
          kubectl create namespace ${{ env.KUBERNETES_NAMESPACE }} --dry-run=client -o yaml | kubectl apply -f -

      - name: Create/Update Kubernetes secrets
        run: |
          kubectl create secret generic akaunting-secrets \
            --from-literal=DB_HOST="${{ secrets.DB_HOST }}" \
            --from-literal=DB_DATABASE="${{ secrets.DB_DATABASE }}" \
            --from-literal=DB_USERNAME="${{ secrets.DB_USERNAME }}" \
            --from-literal=DB_PASSWORD="${{ secrets.DB_PASSWORD }}" \
            --namespace=${{ env.KUBERNETES_NAMESPACE }} \
            --dry-run=client -o yaml | kubectl apply -f -

      - name: Create ECR pull secret
        run: |
          ECR_TOKEN=$(aws ecr get-login-password --region ${{ env.AWS_REGION }})
          kubectl create secret docker-registry aws-ecr-secret \
            --docker-server=${{ secrets.AWS_ACCOUNT_ID }}.dkr.ecr.${{ env.AWS_REGION }}.amazonaws.com \
            --docker-username=AWS \
            --docker-password=$ECR_TOKEN \
            --namespace=${{ env.KUBERNETES_NAMESPACE }} \
            --dry-run=client -o yaml | kubectl apply -f -

      - name: Deploy to Kubernetes
        run: |
          # Mettre à jour l'image dans le deployment
          sed -i "s|IMAGE_TAG|${{ github.sha }}|g" k8s/deployment.yaml
          sed -i "s|ECR_REPO|${{ secrets.AWS_ACCOUNT_ID }}.dkr.ecr.${{ env.AWS_REGION }}.amazonaws.com/${{ env.ECR_REPOSITORY }}|g" k8s/deployment.yaml
          kubectl apply -f k8s/ -n ${{ env.KUBERNETES_NAMESPACE }}

      - name: Wait for deployment rollout
        run: |
          kubectl rollout status deployment/akaunting-app -n ${{ env.KUBERNETES_NAMESPACE }} --timeout=5m

      - name: Verify deployment
        run: |
          kubectl get pods -n ${{ env.KUBERNETES_NAMESPACE }}
          kubectl get services -n ${{ env.KUBERNETES_NAMESPACE }}