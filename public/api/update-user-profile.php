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
    exit(0);
}

if (!isset($_SESSION['user_id'])) {
    ob_end_clean();
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non authentifié'], JSON_UNESCAPED_UNICODE);
    exit;
}

ob_end_clean();

$data = json_decode(file_get_contents('php://input'), true);
$username = $data['username'] ?? '';
$email = $data['email'] ?? '';

if (empty($username) && empty($email)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Au moins un champ doit être fourni'], JSON_UNESCAPED_UNICODE);
    exit;
}

require_once 'db.php';

try {
    $db = Database::connect();
    $userId = $_SESSION['user_id'];
    
    // Vérifier que l'utilisateur existe
    $stmt = $db->prepare("SELECT id, email, username FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Vérifier si l'email est déjà utilisé par un autre utilisateur
    if (!empty($email) && $email !== $user['email']) {
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->execute([$email, $userId]);
        if ($stmt->fetch()) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé'], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    
    // Vérifier si le nom d'utilisateur est déjà utilisé par un autre utilisateur
    if (!empty($username) && $username !== $user['username']) {
        $stmt = $db->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->execute([$username, $userId]);
        if ($stmt->fetch()) {
            http_response_code(409);
            echo json_encode(['success' => false, 'message' => 'Ce nom d\'utilisateur est déjà utilisé'], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
    
    // Construire la requête de mise à jour
    $updates = [];
    $params = [];
    
    if (!empty($username)) {
        $updates[] = "username = ?";
        $params[] = $username;
    }
    
    if (!empty($email)) {
        $updates[] = "email = ?";
        $params[] = $email;
    }
    
    if (empty($updates)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Aucune modification à effectuer'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $params[] = $userId;
    $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = ?";
    $stmt = $db->prepare($sql);
    
    if ($stmt->execute($params)) {
        // Récupérer les données mises à jour
        $stmt = $db->prepare("SELECT id, email, username, accountType, created_at FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $updatedUser = $stmt->fetch(PDO::FETCH_ASSOC);
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Profil mis à jour avec succès',
            'user' => [
                'id' => $updatedUser['id'],
                'email' => $updatedUser['email'],
                'username' => $updatedUser['username'] ?? '',
                'accountType' => $updatedUser['accountType'] ?? 'user',
                'createdAt' => $updatedUser['created_at'] ?? null
            ]
        ], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour'], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

