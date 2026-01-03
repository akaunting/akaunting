# test-local-full-fixed.ps1

# Allow script to run
Set-ExecutionPolicy -Scope Process -ExecutionPolicy Bypass

# Base URL - PHP API container on port 8080
$baseUrl = "http://localhost:8080"

function Test-Result {
    param(
        [bool]$Success,
        [string]$Message
    )
    if ($Success) {
        Write-Host "✅ $Message"
    } else {
        Write-Host "❌ $Message"
    }
}

Write-Host "🚀 Starting full local test for AKAUNTING project..."

# Step 1: Start Docker containers
try {
    docker compose down -v
    docker compose up -d --build
    Start-Sleep -Seconds 10
    Test-Result $true "Docker containers are up"
} catch {
    Test-Result $false "Docker failed to start"
    exit
}

# Step 2: Ensure users table exists
Write-Host "🛠 Ensuring 'users' table exists..."
$createTableQuery = @"
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    username VARCHAR(100) NOT NULL UNIQUE,
    fullName VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    accountType VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
"@

try {
    docker exec -i akaunting_mysql mysql -uroot -prootpassword akaunting -e "$createTableQuery"
    Test-Result $true "Database table 'users' exists or created"
} catch {
    Test-Result $false "Failed to create 'users' table"
}

# Step 3: Test registration
Write-Host "🧪 Testing user registration..."
$user = @{
    email = "test@test.com"
    username = "testuser"
    fullName = "Test User"
    password = "password123"
    accountType = "admin"
} | ConvertTo-Json

try {
    $response = Invoke-RestMethod -Uri "$baseUrl/register.php" -Method POST -ContentType "application/json" -Body $user
    Test-Result ($response.success -eq $true) "User registration OK"
} catch {
    Test-Result $false "User registration FAILED"
}

# Step 4: Test login
Write-Host "🧪 Testing login..."
$login = @{
    email = "test@test.com"
    password = "password123"
} | ConvertTo-Json

try {
    $loginResp = Invoke-RestMethod -Uri "$baseUrl/login.php" -Method POST -ContentType "application/json" -Body $login
    Test-Result ($loginResp.success -eq $true) "Login OK"
} catch {
    Test-Result $false "Login FAILED"
}

# Step 5: Test check-auth
Write-Host "🧪 Testing check-auth..."
try {
    $auth = Invoke-RestMethod -Uri "$baseUrl/check-auth.php"
    Test-Result ($auth.authenticated -eq $true) "Check-auth OK"
} catch {
    Test-Result $false "Check-auth FAILED"
}

Write-Host "✅ Local test complete!"
Write-Host ""
Write-Host "📋 Test Results Summary:"
Write-Host " - API Endpoint: http://localhost:8080"
Write-Host " - Frontend URLs:"
Write-Host "   • Register: http://localhost:8000/public/devsecops/register.html"
Write-Host "   • Login:    http://localhost:8000/public/devsecops/index.html"
Write-Host ""
Write-Host "💡 To verify data in database, run:"
Write-Host "   docker exec -it akaunting_mysql mysql -uakaunting -pakauntingpass akaunting -e 'SELECT * FROM users;'"

