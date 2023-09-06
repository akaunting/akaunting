<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/spec')
    ->name('*.php')
;

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'single_line_throw' => false,
    ])
    ->setFinder($finder)
;
