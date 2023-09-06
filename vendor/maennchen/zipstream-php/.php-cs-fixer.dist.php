<?php

declare(strict_types=1);

/**
 * PHP-CS-Fixer config for ZipStream-PHP
 * @author Nicolas CARPi <nico-git@deltablot.email>
 * @copyright 2022 Nicolas CARPi
 * @see https://github.com/maennchen/ZipStream-PHP
 * @license MIT
 * @package maennchen/ZipStream-PHP
 */

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->exclude('.github')
    ->exclude('.phpdoc')
    ->exclude('docs')
    ->exclude('tools')
    ->exclude('vendor')
    ->in(__DIR__);

$config = new Config();
return $config->setRules([
        '@PER' => true,
        '@PER:risky' => true,
        '@PHP82Migration' => true,
        '@PHPUnit84Migration:risky' => true,
        'array_syntax' => ['syntax' => 'short'],
        'class_attributes_separation' => true,
        'declare_strict_types' => true,
        'dir_constant' => true,
        'is_null' => true,
        'no_homoglyph_names' => true,
        'no_null_property_initialization' => true,
        'no_php4_constructor' => true,
        'no_unused_imports' => true,
        'no_useless_else' => true,
        'non_printable_character' => true,
        'ordered_imports' => true,
        'ordered_class_elements' => true,
        'php_unit_construct' => true,
        'pow_to_exponentiation' => true,
        'psr_autoloading' => true,
        'random_api_migration' => true,
        'return_assignment' => true,
        'self_accessor' => true,
        'semicolon_after_instruction' => true,
        'short_scalar_cast' => true,
        'simplified_null_return' => true,
        'single_blank_line_before_namespace' => true,
        'single_class_element_per_statement' => true,
        'single_line_comment_style' => true,
        'single_quote' => true,
        'space_after_semicolon' => true,
        'standardize_not_equals' => true,
        'strict_param' => true,
        'ternary_operator_spaces' => true,
        'trailing_comma_in_multiline' => true,
        'trim_array_spaces' => true,
        'unary_operator_spaces' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_functions' => true,
            'import_constants' => true,
        ],
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true);
