<?php
ob_start();
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    ob_end_clean();
    exit(0);
}

ob_end_clean();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Non authentifié'
    ], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once 'db.php';

try {
    $db = Database::connect();
    $stmt = $db->prepare("SELECT id, email, username, accountType, created_at FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'user' => [
                'id' => $user['id'],
                'email' => $user['email'],
                'username' => $user['username'] ?? '',
                'accountType' => $user['accountType'] ?? 'user',
                'createdAt' => $user['created_at'] ?? null,
                'accountStatus' => 'active',
                'lastLogin' => date('Y-m-d H:i:s')
            ]
        ], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(404);
        echo json_encode([
            'success' => false,
            'message' => 'Utilisateur non trouvé'
        ], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}

