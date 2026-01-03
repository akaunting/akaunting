# Quick API Test Script

Write-Host "🧪 Quick API Test" -ForegroundColor Cyan
Write-Host ""

# Test 1: Check if API is accessible
Write-Host "1. Testing API accessibility..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://localhost:8080/check-auth.php" -Method GET -UseBasicParsing
    Write-Host "   ✅ API is accessible (Status: $($response.StatusCode))" -ForegroundColor Green
} catch {
    Write-Host "   ❌ API is not accessible. Make sure containers are running:" -ForegroundColor Red
    Write-Host "      docker compose up -d" -ForegroundColor Yellow
    exit
}

# Test 2: Registration
Write-Host ""
Write-Host "2. Testing user registration..." -ForegroundColor Yellow
$testUser = @{
    email = "quicktest@test.com"
    username = "quicktest"
    fullName = "Quick Test User"
    password = "test123"
    accountType = "admin"
} | ConvertTo-Json

try {
    $regResponse = Invoke-RestMethod -Uri "http://localhost:8080/register.php" -Method POST -ContentType "application/json" -Body $testUser
    if ($regResponse.success) {
        Write-Host "   ✅ Registration successful" -ForegroundColor Green
    } else {
        Write-Host "   ⚠️  Registration returned: $($regResponse.message)" -ForegroundColor Yellow
    }
} catch {
    Write-Host "   ❌ Registration failed: $_" -ForegroundColor Red
}

# Test 3: Login
Write-Host ""
Write-Host "3. Testing user login..." -ForegroundColor Yellow
$loginData = @{
    email = "quicktest@test.com"
    password = "test123"
} | ConvertTo-Json

try {
    $loginResponse = Invoke-RestMethod -Uri "http://localhost:8080/login.php" -Method POST -ContentType "application/json" -Body $loginData
    if ($loginResponse.success) {
        Write-Host "   ✅ Login successful" -ForegroundColor Green
    } else {
        Write-Host "   ❌ Login failed: $($loginResponse.message)" -ForegroundColor Red
    }
} catch {
    Write-Host "   ❌ Login request failed: $_" -ForegroundColor Red
}

# Test 4: Verify in database
Write-Host ""
Write-Host "4. Verifying user in database..." -ForegroundColor Yellow
try {
    $dbResult = docker exec akaunting_mysql mysql -uakaunting -pakauntingpass akaunting -e "SELECT email, username FROM users WHERE email='quicktest@test.com';" 2>&1
    if ($dbResult -match "quicktest@test.com") {
        Write-Host "   ✅ User found in database" -ForegroundColor Green
    } else {
        Write-Host "   ⚠️  User not found in database" -ForegroundColor Yellow
    }
} catch {
    Write-Host "   ⚠️  Could not verify database (container might not be ready)" -ForegroundColor Yellow
}

Write-Host ""
Write-Host "✅ Quick test complete!" -ForegroundColor Green
Write-Host ""
Write-Host "📋 Next steps:" -ForegroundColor Cyan
Write-Host "   • Test frontend: http://localhost:8000/public/devsecops/register.html"
Write-Host "   • View all users: docker exec -it akaunting_mysql mysql -uakaunting -pakauntingpass akaunting -e 'SELECT * FROM users;'"


