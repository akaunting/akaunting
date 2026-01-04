<?php
ob_start();
session_start();
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
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

$currentPath = $_GET['path'] ?? '';

// Chemin de base pour les fichiers utilisateur
$basePath = __DIR__ . '/user_files/' . $_SESSION['user_id'];
$targetPath = $basePath;

if ($currentPath) {
    $targetPath = $basePath . '/' . trim($currentPath, '/');
}

// Vérifier que le chemin est dans le répertoire autorisé
$realBasePath = realpath($basePath);
$realTargetPath = realpath($targetPath);
if ($realTargetPath === false || strpos($realTargetPath, $realBasePath) !== 0) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Chemin non autorisé'], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    if (!is_dir($targetPath)) {
        if (!is_dir($basePath)) {
            mkdir($basePath, 0755, true);
        }
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Répertoire introuvable'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $files = [];
    $items = scandir($targetPath);
    
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue;
        }
        
        $itemPath = $targetPath . '/' . $item;
        $relativePath = $currentPath ? $currentPath . '/' . $item : $item;
        
        $files[] = [
            'name' => $item,
            'path' => $relativePath,
            'type' => is_dir($itemPath) ? 'folder' : 'file',
            'size' => is_file($itemPath) ? filesize($itemPath) : 0,
            'modified' => filemtime($itemPath)
        ];
    }
    
    // Trier: dossiers d'abord, puis fichiers
    usort($files, function($a, $b) {
        if ($a['type'] === $b['type']) {
            return strcmp($a['name'], $b['name']);
        }
        return $a['type'] === 'folder' ? -1 : 1;
    });
    
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'files' => $files,
        'path' => $currentPath
    ], JSON_UNESCAPED_UNICODE);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

