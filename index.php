<?php

/**
 * @package     Akaunting
 * @copyright   2017-2021 Akaunting. All rights reserved.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://akaunting.com
 */

// Register the auto-loader
require(__DIR__ . '/bootstrap/autoload.php');

// Load the app
$app = require_once(__DIR__ . '/bootstrap/app.php');

// Run the app
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
