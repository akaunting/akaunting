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
$fileName = $data['fileName'] ?? '';
$fileContent = $data['content'] ?? '';
$currentPath = $data['path'] ?? '';

if (!$fileName) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nom du fichier requis'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Sécuriser le nom du fichier
$fileName = basename($fileName);
if (empty($fileName)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Nom de fichier invalide'], JSON_UNESCAPED_UNICODE);
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
    // Normaliser le chemin pour éviter les ../
    $targetDir = realpath($targetDir);
    if ($targetDir === false) {
        // Le répertoire n'existe pas, créer le chemin
        $targetDir = $realBasePath . '/' . trim($currentPath, '/');
    }
}

// Vérifier que le répertoire cible est dans le répertoire autorisé
$realTargetDir = realpath($targetDir);
if ($realTargetDir === false) {
    // Le répertoire n'existe pas encore, vérifier que le chemin est valide
    $normalizedPath = str_replace('\\', '/', $targetDir);
    $normalizedBase = str_replace('\\', '/', $realBasePath);
    if (strpos($normalizedPath, $normalizedBase) !== 0) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Chemin non autorisé'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    // Créer le répertoire parent s'il n'existe pas
    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0755, true)) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Impossible de créer le répertoire'], JSON_UNESCAPED_UNICODE);
            exit;
        }
        $realTargetDir = realpath($targetDir);
    }
} else {
    // Vérifier que le répertoire est bien dans le répertoire autorisé
    $normalizedTarget = str_replace('\\', '/', $realTargetDir);
    $normalizedBase = str_replace('\\', '/', $realBasePath);
    if (strpos($normalizedTarget, $normalizedBase) !== 0) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Chemin non autorisé'], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

$targetPath = ($realTargetDir ?: $targetDir) . '/' . $fileName;

try {
    
    if (file_exists($targetPath)) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Le fichier existe déjà'], JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    if (file_put_contents($targetPath, $fileContent) !== false) {
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'message' => 'Fichier créé avec succès',
            'path' => $targetPath
        ], JSON_UNESCAPED_UNICODE);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la création du fichier'], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

