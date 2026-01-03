# Fix MySQL permissions - Run this script
Write-Host "Fixing MySQL user permissions..." -ForegroundColor Yellow

# Create SQL file
$sql = @"
CREATE USER IF NOT EXISTS 'akaunting'@'%' IDENTIFIED BY 'akauntingpass';
GRANT ALL PRIVILEGES ON akaunting.* TO 'akaunting'@'%';
FLUSH PRIVILEGES;
"@

$sql | Out-File -FilePath fix-temp.sql -Encoding utf8

# Execute SQL
Write-Host "Executing SQL to create user..." -ForegroundColor Cyan
Get-Content fix-temp.sql | docker exec -i akaunting_mysql mysql -uroot -prootpassword 2>&1

# Cleanup
Remove-Item fix-temp.sql -ErrorAction SilentlyContinue

Write-Host "Testing connection..." -ForegroundColor Cyan
docker exec php_api php /var/www/html/check-db.php


