<?php

namespace App\Utilities;

use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class Console
{
    public static function run($string, $all_output = false, $timeout = 0)
    {
        $command = static::formatCommandString($string);

        $process = Process::fromShellCommandline($command, base_path());
        $process->setTimeout($timeout);

        $process->run();

        if ($process->isSuccessful()) {
            return true;
        }

        $output = $all_output ? $process->getOutput() : $process->getErrorOutput();

        logger($output);

        return $output;
    }

    public static function getPhpBinary()
    {
        return (new PhpExecutableFinder)->find(false) ?? 'php';
    }

    public static function getArtisanBinary()
    {
        return defined('ARTISAN_BINARY') ? ARTISAN_BINARY : 'artisan';
    }

    public static function formatCommandString($string)
    {
        return sprintf('%s %s %s', static::getPhpBinary(), static::getArtisanBinary(), $string);
    }
}
