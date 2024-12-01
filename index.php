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

$addonXml = file_exists($addonXmlPath)
    ? simplexml_load_file($addonXmlPath)
    : null;
$phpVersion = substr((string)($addonXml?->compatibility?->php_version?->min ?? '7.4'), 0, 3);

foreach ([
     PhpCsFixerRunner::class,
     AppPhpStan\Runner::class,
] as $runnerClass) {
    try {
        $runner = new $runnerClass($phpVersion, $path);
        if (!$runner->run()) {
            exit(1);
        }
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage() . "\n";
        exit(1);
    }
}
