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
