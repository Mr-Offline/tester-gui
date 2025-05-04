<?php

namespace App\Utils;

class Helper
{
    public static function checkTestFramework(string $projectPath)
    {
        $phpunitFile = realpath($projectPath . DIRECTORY_SEPARATOR . 'phpunit.xml');
        $pestFile = realpath($projectPath . DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR . 'Pest.php');

        if (file_exists($pestFile)) {
            return 'PestPHP';
        }

        if (file_exists($phpunitFile)) {
            return 'PHPUnit';
        }

        return 'Unknown';
    }
}
