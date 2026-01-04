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

if (!isset($_SESSION['user_id'])) {
    ob_end_clean();
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Non authentifié'], JSON_UNESCAPED_UNICODE);
    exit;
}

ob_end_clean();

$filePath = $_GET['path'] ?? '';

if (empty($filePath)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Chemin de fichier requis'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Chemin de base pour les fichiers utilisateur
$basePath = __DIR__ . '/user_files/' . $_SESSION['user_id'];
$targetPath = $basePath . '/' . trim($filePath, '/');

$realBasePath = realpath($basePath);
if ($realBasePath === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Répertoire utilisateur introuvable'], JSON_UNESCAPED_UNICODE);
    exit;
}

$realTargetPath = realpath($targetPath);
if ($realTargetPath === false || !is_file($realTargetPath)) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Fichier introuvable'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Vérifier que le chemin est dans le répertoire autorisé
$normalizedTarget = str_replace('\\', '/', $realTargetPath);
$normalizedBase = str_replace('\\', '/', $realBasePath);
if (strpos($normalizedTarget, $normalizedBase) !== 0) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Chemin non autorisé'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $content = file_get_contents($realTargetPath);
    if ($content === false) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la lecture du fichier'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'content' => $content,
        'name' => basename($realTargetPath),
        'path' => $filePath
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

