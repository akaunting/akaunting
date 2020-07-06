<?php

// Define minimum supported PHP version
define('AKAUNTING_PHP', '7.2.5');

// Check PHP version
if (version_compare(PHP_VERSION, AKAUNTING_PHP, '<')) {
    die('Error: Ask your hosting provider to use PHP ' . AKAUNTING_PHP . ' or higher for both HTTP and CLI.');
}

define('LARAVEL_START', microtime(true));

// workaround until https://github.com/lorisleiva/laravel-search-string/pull/21
if (! function_exists('resolve')) {
    /**
     * Resolve a service from the container.
     *
     * @param  string  $name
     * @param  array  $parameters
     * @return mixed
     */
    function resolve($name, array $parameters = [])
    {
        return app($name, $parameters);
    }
}

// Load composer for core
require __DIR__ . '/../vendor/autoload.php';

// Load composer for modules
foreach (glob(__DIR__ . '/../modules/*') as $folder) {
    $autoload = $folder . '/vendor/autoload.php';

    if (!is_file($autoload)) {
        continue;
    }

    require $autoload;
}
