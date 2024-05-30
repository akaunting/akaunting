<?php

// Define minimum supported PHP version
define('AKAUNTING_PHP', '7.4.1');

// Check PHP version
if (version_compare(PHP_VERSION, AKAUNTING_PHP, '<')) {
    $message = 'Error: Ask your hosting provider to use PHP ' . AKAUNTING_PHP . ' or higher for HTTP, CLI, and php command.' . PHP_EOL . PHP_EOL . 'Current PHP version: ' . PHP_VERSION . PHP_EOL;

    if (defined('STDOUT')) {
        fwrite(STDOUT, $message);
    } else {
        echo($message);
    }

    die(1);
}

// Load composer for core
require __DIR__ . '/../vendor/autoload.php';
