<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

/*
 * Define folders to fix
 */
$finder = Finder::create()
    ->in([
        __DIR__ .'/src',
        __DIR__ .'/tests',
    ]);
;

/*
 * Do the magic
 */
return Config::create()
    ->setUsingCache(false)
    ->setRules([
        '@PSR2'              => true,
        '@Symfony'           => true,

        'align_multiline_comment' => true,
        'blank_line_after_opening_tag' => true,
        'single_blank_line_before_namespace' => true,
        'no_unused_imports' => true,
        'binary_operator_spaces' => ['default' => null],
    ])
    ->setFinder($finder)
;