<?php
ob_start();
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    ob_end_clean();
    http_response_code(200);
    exit;
}

require_once 'db.php';
ob_end_clean();

$data = json_decode(file_get_contents('php://input'), true);

$usernameOrEmail = $data['username'] ?? '';
$password = $data['password'] ?? '';

if (!$usernameOrEmail || !$password) {
    echo json_encode([
        'success' => false,
        'message' => 'Username/email and password required'
    ]);
    exit;
}

try {
    $db = Database::connect();

    $stmt = $db->prepare("
        SELECT id, email, username, password, accountType
        FROM users
        WHERE email = ? OR username = ?
    ");
    $stmt->execute([$usernameOrEmail, $usernameOrEmail]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($password, $user['password'])) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'Invalid credentials'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // Créer la session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_username'] = $user['username'];
    $_SESSION['user_accountType'] = $user['accountType'];

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => 'Login successful',
        'user' => [
            'id' => $user['id'],
            'email' => $user['email'],
            'username' => $user['username'],
            'accountType' => $user['accountType']
        ]
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}
