<?php
try {
    $pdo = new PDO('mysql:host=mysql;dbname=akaunting', 'akaunting', 'akauntingpass');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection successful!\n\n";
    
    // Check if users table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "📋 Users table exists.\n\n";
        
        // Get all users
        $stmt = $pdo->query("SELECT id, email, username, fullName, accountType, created_at FROM users");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($users) > 0) {
            echo "👥 Found " . count($users) . " user(s):\n";
            echo str_repeat("-", 80) . "\n";
            foreach ($users as $user) {
                echo "ID: {$user['id']}\n";
                echo "Email: {$user['email']}\n";
                echo "Username: {$user['username']}\n";
                echo "Full Name: {$user['fullName']}\n";
                echo "Account Type: {$user['accountType']}\n";
                echo "Created: {$user['created_at']}\n";
                echo str_repeat("-", 80) . "\n";
            }
        } else {
            echo "📭 No users found in the database yet.\n";
            echo "   Register a user at: http://localhost:8000/public/devsecops/register.html\n";
        }
    } else {
        echo "⚠️  Users table does not exist yet.\n";
        echo "   It will be created automatically when you register the first user.\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

