<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'single_quote' => true,
        'no_unused_imports' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'class_attributes_separation' => [
            'elements' => [
                'method' => 'one',
            ],
        ],
//        'phpdoc_to_return_type' => true,
        'no_trailing_comma_in_singleline_function_call' => true,
    ])
    ->setLineEnding("\r\n")
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
    );
