<?php

use PhpCsFixer\Config;

$finder = Symfony\Component\Finder\Finder::create()
    ->notPath('vendor')
    ->notPath('test-stubs-nova')
    ->notPath('tests/Stubs')
    ->in(__DIR__)
    ->name('*.php');

$config = new Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR2' => true,
        'array_syntax' => ['syntax' => 'short'],
        'no_unused_imports' => true,
        'declare_strict_types' => true
    ])
    ->setFinder($finder);
