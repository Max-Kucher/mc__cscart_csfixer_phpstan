<?php

require_once __DIR__ . '/vendor/autoload.php';

if ($argc < 2) {
    echo "Usage: php index.php <path_to_code>\n";
    exit(1);
}

$path = $argv[1];

if (!is_dir($path)) {
    echo "Error: The specified path '$path' is not a directory.\n";
    exit(1);
}

$addonName = basename($path);
$addonXmlPath = "$path/app/addons/$addonName/addon.xml";

if (!file_exists($addonXmlPath)) {
    echo "Error: addon.xml file not found in the specified path.\n";
    exit(1);
}

$addonXml = simplexml_load_file($addonXmlPath);
$phpVersion = substr((string)($addonXml->compatibility?->php_version?->min ?? '7.4'), 0, 3);

$configPath = realpath(__DIR__ . '/.php-cs-fixer.php');
if (!$configPath) {
    echo "Error: PHP CS Fixer config file not found.\n";
    exit(1);
}

try {
    $fixer = new PhpCsFixerRunner($phpVersion, $configPath);
    if (!$fixer->run($path)) {
        exit(1);
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
    exit(1);
}
