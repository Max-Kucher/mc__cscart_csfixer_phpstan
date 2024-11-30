<?php

class PhpCsFixerRunner
{
    private string $dockerImage;
    private string $dockerTag;
    private string $configPath;

    public function __construct(string $phpVersion, string $configPath)
    {
        $this->dockerImage = 'ghcr.io/php-cs-fixer/php-cs-fixer';
        $this->dockerTag = "3.57-php$phpVersion";
        $this->configPath = $configPath;
    }

    public function run(string $path): bool
    {
        $realPath = realpath($path);
        if (!$realPath) {
            throw new InvalidArgumentException("Invalid path: $path");
        }

        $command = sprintf(
            'docker run --rm -v %s:/code -v %s:/config %s:%s fix /code/app --config=/config/.php-cs-fixer.php',
            escapeshellarg($realPath),
            escapeshellarg(dirname($this->configPath)),
            $this->dockerImage,
            $this->dockerTag
        );

        echo "Running PHP CS Fixer through Docker...\n";
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            echo "Error: PHP CS Fixer failed.\n";
            echo implode("\n", $output);
            return false;
        }

        $cacheFile = "$realPath/.php-cs-fixer.cache";
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }

        echo "PHP CS Fixer completed successfully.\n";
        return true;
    }
}