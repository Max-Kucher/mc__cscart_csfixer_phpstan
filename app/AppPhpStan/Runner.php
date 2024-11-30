<?php

namespace AppPhpStan;

class Runner implements \Runner
{
    private string $dockerImage;

    public function __construct(string $phpVersion)
    {
        $this->dockerImage = "php:$phpVersion-cli";
    }

    public function run(string $path): bool
    {
        // Чтение include_pathes.txt
        $includePathsFile = ROOT_DIR . '/include_pathes.txt';
        if (!file_exists($includePathsFile)) {
            echo "Error: include_pathes.txt not found.\n";
            exit(1);
        }

        $corePath = null;
        $includePaths = file($includePathsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($includePaths as $_path) {
            $realPath = realpath($_path);
            if ($realPath && is_dir($realPath)) {
                $corePath = $realPath;
                break;
            }
        }

        if (!$corePath) {
            echo "Error: Core path could not be determined from include_pathes.txt.\n";
            exit(1);
        }

        // Статический конфиг
        $configPath = realpath(ROOT_DIR . '/phpstan.neon');
        if (!$configPath) {
            echo "Error: phpstan.neon file not found.\n";
            exit(1);
        }

        // Docker команда
        $command = sprintf(
            "docker run --rm -v %s:/code/core -v %s:/code/addon -v %s:/project -w /project $this->dockerImage php vendor/bin/phpstan analyse /code/addon/app --configuration=/project/phpstan.neon",
            escapeshellarg($corePath),
            escapeshellarg(realpath($path)),
            escapeshellarg(dirname($configPath)),
        );

        // Запуск команды
        echo "Running PHPStan through Docker...\n$command\n";
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            echo "Error: PHPStan failed.\n";
            echo implode("\n", $output);
            exit($returnVar);
        }

        echo "PHPStan completed successfully.\n";
        return true;
    }
}
