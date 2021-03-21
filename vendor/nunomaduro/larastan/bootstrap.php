<?php

declare(strict_types=1);

use NunoMaduro\Larastan\ApplicationResolver;

define('LARAVEL_START', microtime(true));

if (file_exists($applicationPath = getcwd().'/bootstrap/app.php')) { // Applications and Local Dev
    $app = require $applicationPath;
} else { // Packages
    $app = ApplicationResolver::resolve();
}

if ($app instanceof \Illuminate\Contracts\Foundation\Application) {
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
} elseif ($app instanceof \Laravel\Lumen\Application) {
    $app->boot();
}
