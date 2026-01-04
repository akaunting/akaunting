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
$folderName = $data['folderName'] ?? '';
$currentPath = $data['path'] ?? '';

if (!$folderName) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nom du dossier requis'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Sécuriser le nom du dossier
$folderName = preg_replace('/[^a-zA-Z0-9_-]/', '', $folderName);
if (empty($folderName)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nom de dossier invalide'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Chemin de base pour les fichiers utilisateur
$basePath = __DIR__ . '/user_files/' . $_SESSION['user_id'];

// Créer le répertoire de base s'il n'existe pas
if (!is_dir($basePath)) {
    if (!mkdir($basePath, 0755, true)) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Impossible de créer le répertoire utilisateur'], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

$realBasePath = realpath($basePath);
if ($realBasePath === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Chemin de base invalide'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Construire le chemin cible
$targetDir = $realBasePath;
if ($currentPath) {
    $targetDir = $realBasePath . '/' . trim($currentPath, '/');
    // Normaliser le chemin
    $normalizedPath = str_replace('\\', '/', $targetDir);
    $normalizedBase = str_replace('\\', '/', $realBasePath);
    if (strpos($normalizedPath, $normalizedBase) !== 0) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Chemin non autorisé'], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

$targetPath = $targetDir . '/' . $folderName;

// Vérifier que le chemin est dans le répertoire autorisé
$normalizedTarget = str_replace('\\', '/', $targetPath);
$normalizedBase = str_replace('\\', '/', $realBasePath);
if (strpos($normalizedTarget, $normalizedBase) !== 0) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Chemin non autorisé'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    
    if (file_exists($targetPath)) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Le dossier existe déjà'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    if (mkdir($targetPath, 0755, true)) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Dossier créé avec succès',
            'path' => $targetPath
        ], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la création du dossier'], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

