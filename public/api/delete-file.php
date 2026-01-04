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
$filePath = $data['path'] ?? '';
$fileName = $data['name'] ?? '';

if (empty($filePath) && empty($fileName)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Chemin ou nom de fichier requis'], JSON_UNESCAPED_UNICODE);
    exit;
}

// Chemin de base pour les fichiers utilisateur
$basePath = __DIR__ . '/user_files/' . $_SESSION['user_id'];

// Construire le chemin complet
$targetPath = $basePath;
if ($filePath) {
    $targetPath = $basePath . '/' . trim($filePath, '/');
} elseif ($fileName) {
    $targetPath = $basePath . '/' . basename($fileName);
}

$realBasePath = realpath($basePath);
if ($realBasePath === false) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Répertoire utilisateur introuvable'], JSON_UNESCAPED_UNICODE);
    exit;
}

$realTargetPath = realpath($targetPath);
if ($realTargetPath === false) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Fichier ou dossier introuvable'], JSON_UNESCAPED_UNICODE);
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
    if (is_dir($realTargetPath)) {
        // Supprimer un dossier récursivement
        function deleteDirectory($dir) {
            if (!is_dir($dir)) {
                return false;
            }
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                $filePath = $dir . '/' . $file;
                if (is_dir($filePath)) {
                    deleteDirectory($filePath);
                } else {
                    unlink($filePath);
                }
            }
            return rmdir($dir);
        }
        
        if (deleteDirectory($realTargetPath)) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Dossier supprimé avec succès'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression du dossier'], JSON_UNESCAPED_UNICODE);
        }
    } elseif (is_file($realTargetPath)) {
        // Supprimer un fichier
        if (unlink($realTargetPath)) {
            http_response_code(200);
            echo json_encode([
                'success' => true,
                'message' => 'Fichier supprimé avec succès'
            ], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression du fichier'], JSON_UNESCAPED_UNICODE);
        }
    } else {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Fichier ou dossier introuvable'], JSON_UNESCAPED_UNICODE);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
}

