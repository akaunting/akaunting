<?php
require_once 'db.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$data = json_decode(file_get_contents('php://input'), true);

$email = $data['email'] ?? '';
$username = $data['username'] ?? '';
$fullName = $data['fullName'] ?? '';
$password = $data['password'] ?? '';
$accountType = $data['accountType'] ?? '';

if (!$email || !$password || !$username || !$fullName) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

try {
    $db = Database::connect();

    // CREATE TABLE FIRST
    $db->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            email VARCHAR(255) NOT NULL UNIQUE,
            username VARCHAR(100) NOT NULL UNIQUE,
            fullName VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            accountType VARCHAR(50) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

    // Check if user exists by email or username
    $stmt = $db->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);

    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'User already exists']);
        exit;
    }

    // Insert
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (email, username, fullName, password, accountType) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$email, $username, $fullName, $hashed, $accountType]);

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
