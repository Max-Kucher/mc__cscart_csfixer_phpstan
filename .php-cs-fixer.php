<?php

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        'array_syntax' => ['syntax' => 'short'],
        'single_quote' => true,
        'no_unused_imports' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
    ])
    ->setLineEnding("\r\n")
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__)
    );
