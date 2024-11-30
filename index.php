<?php

if ($argc < 2) {
    echo "Usage: php fix.php <path_to_code>\n";
    exit(1);
}

$path = $argv[1];

if (!is_dir($path)) {
    echo "Error: The specified path '$path' is not a directory.\n";
    exit(1);
}

$executivePath = realpath(__DIR__ . '/vendor/bin/php-cs-fixer');

$command = sprintf($executivePath . ' fix %s --config=.php-cs-fixer.php', escapeshellarg($path));

shell_exec($command);
