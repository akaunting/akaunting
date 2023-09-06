<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests');

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@Symfony' => true,
    '@Symfony:risky' => true,
    'native_function_invocation' => ['include'=> ['@all']],
    'native_constant_invocation' => true,
    'ordered_imports' => true,
    'declare_strict_types' => false,
    'linebreak_after_opening_tag' => false,
    'single_import_per_statement' => false,
    'blank_line_after_opening_tag' => false,
    'concat_space' => ['spacing'=>'one'],
    'phpdoc_align' => ['align'=>'left'],
])
    ->setRiskyAllowed(true)
    ->setFinder($finder);
