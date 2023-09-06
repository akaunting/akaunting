<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->name('*.php')
;

$config = (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;

return $config;
