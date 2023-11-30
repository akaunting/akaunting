<?php

/**
 * @package     Akaunting
 * @copyright   2017-2023 Akaunting. All rights reserved.
 * @license     BSL; see LICENSE.txt
 * @link        https://akaunting.com
 */

define('LARAVEL_START', microtime(true));

// Check for maintenance
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the auto-loader
require __DIR__ . '/../bootstrap/autoload.php';

// Load the app
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Run the app
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
)->send();

$kernel->terminate($request, $response);
