<?php

/**
 * Router script for `php artisan serve`.
 *
 * The dev server's document root is public/, but Akaunting builds its asset
 * URLs with a /public prefix because a real deployment points the document
 * root at the project root. Without resolving that prefix here every asset
 * 404s under `artisan serve`. A real web server never runs this file.
 */

// The document root of a real deployment is the project root, which would make
// this file reachable over the web. Only the built-in server may run it.
if (PHP_SAPI !== 'cli-server') {
    http_response_code(404);

    exit;
}

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if (str_starts_with($uri, '/public/')) {
    $public = realpath(__DIR__ . '/public');
    $file = realpath(__DIR__ . '/public' . substr($uri, strlen('/public')));

    // realpath() also keeps ../ out of the served path
    if ($public && $file && str_starts_with($file, $public) && is_file($file)) {
        $types = [
            'css'   => 'text/css',
            'js'    => 'text/javascript',
            'mjs'   => 'text/javascript',
            'json'  => 'application/json',
            'map'   => 'application/json',
            'svg'   => 'image/svg+xml',
            'png'   => 'image/png',
            'jpg'   => 'image/jpeg',
            'jpeg'  => 'image/jpeg',
            'gif'   => 'image/gif',
            'webp'  => 'image/webp',
            'ico'   => 'image/x-icon',
            'woff'  => 'font/woff',
            'woff2' => 'font/woff2',
            'ttf'   => 'font/ttf',
            'otf'   => 'font/otf',
            'eot'   => 'application/vnd.ms-fontobject',
        ];

        $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        header('Content-Type: ' . ($types[$extension] ?? 'application/octet-stream'));
        header('Content-Length: ' . filesize($file));

        readfile($file);

        return true;
    }
}

if ($uri !== '/' && file_exists(__DIR__ . '/public' . $uri)) {
    return false;
}

require_once __DIR__ . '/public/index.php';
