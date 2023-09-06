<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
;

$config = new PhpCsFixer\Config;

$config
    ->setRules([
        '@PSR2' => true,
    ])
    ->setFinder($finder)
;

return $config;
