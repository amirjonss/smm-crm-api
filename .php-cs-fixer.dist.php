<?php

$finder = (new PhpCsFixer\Finder())
    ->files()
    ->in([
        __DIR__ . '/src/',
        //        __DIR__ . '/tests/',
    ])
    ->name('*.php')
    ->exclude([
        'var',
        'vendor',
        'migrations',
    ])
;

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'concat_space' => ['spacing' => 'one'],
        'no_unused_imports' => true,
        'array_syntax' => ['syntax' => 'short'],
        'ordered_imports' => ['sort_algorithm' => 'alpha'],
        'yoda_style' => false,
        'phpdoc_to_comment' => false,
        'no_extra_blank_lines' => [
            'tokens' => [
                'extra',
                'throw',
                'use',
            ],
        ],
        'return_type_declaration' => ['space_before' => 'none'],
        'single_line_throw' => false,
        'native_function_invocation' => false,
        'no_superfluous_phpdoc_tags' => false,
        'increment_style' => false,
        'native_constant_invocation' => false,
    ])
    ->setFinder($finder)
;
