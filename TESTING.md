# Testing Guide

## Quick Test (Automated)

Run the PowerShell test script:
```powershell
.\test-local-full.ps1
```

This will:
1. Start all Docker containers
2. Create the users table if needed
3. Test user registration
4. Test user login
5. Test authentication check

## Manual Testing

### 1. Start Containers
```powershell
docker compose up --build -d
```

Wait 10-15 seconds for containers to be ready.

### 2. Test API Endpoints Directly

#### Test Registration
```powershell
$body = @{
    email = "test@example.com"
    username = "testuser"
    fullName = "Test User"
    password = "password123"
    accountType = "admin"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8080/register.php" -Method POST -ContentType "application/json" -Body $body
```

Expected response: `{"success": true}`

#### Test Login
```powershell
$body = @{
    email = "test@example.com"
    password = "password123"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8080/login.php" -Method POST -ContentType "application/json" -Body $body
```

Expected response: `{"success": true, "message": "Login successful"}`

#### Test Check Auth
```powershell
Invoke-RestMethod -Uri "http://localhost:8080/check-auth.php"
```

### 3. Test Through Frontend

1. Open browser: `http://localhost:8000/public/devsecops/register.html`
2. Fill in the registration form
3. Submit and verify success message
4. Go to: `http://localhost:8000/public/devsecops/index.html`
5. Try logging in with the registered credentials

### 4. Verify Database

Check if user was saved in MySQL:
```powershell
docker exec -it akaunting_mysql mysql -uakaunting -pakauntingpass akaunting -e "SELECT * FROM users;"
```

Or using root:
```powershell
docker exec -it akaunting_mysql mysql -uroot -prootpassword akaunting -e "SELECT id, email, username, fullName, accountType, created_at FROM users;"
```

### 5. Check Container Logs

```powershell
# PHP API container logs
docker logs php_api

# MySQL container logs
docker logs akaunting_mysql

# All containers status
docker compose ps
```

## Troubleshooting

### API not responding
- Check if container is running: `docker ps`
- Check logs: `docker logs php_api`
- Verify port 8080 is not in use

### Database connection errors
- Verify MySQL container is running: `docker ps | findstr mysql`
- Check MySQL logs: `docker logs akaunting_mysql`
- Verify network: `docker network inspect akaunting_akaunting_network`

### Frontend can't reach API
- Open browser console (F12) and check for CORS errors
- Verify API is accessible: `curl http://localhost:8080/check-auth.php`
- Check if frontend URL is correct (should be `http://localhost:8080/...`)


