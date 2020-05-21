<?php

namespace App\Utilities;

use Illuminate\Support\ProcessUtils;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class Console
{
    public static function run($string, $all_output = false, $timeout = 0)
    {
        $command = static::formatCommandString($string);

        logger('Console command:: ' . $command);

        $process = Process::fromShellCommandline($command, base_path());
        $process->setTimeout($timeout);

        $process->run();

        if ($process->isSuccessful()) {
            return true;
        }

        $output = $all_output ? $process->getOutput() : $process->getErrorOutput();

        logger('Console output:: ' . $output);

        return $output;
    }

    public static function getPhpBinary()
    {
        $bin = ProcessUtils::escapeArgument((new PhpExecutableFinder)->find(false));

        return !empty($bin) ? $bin : 'php';
    }

    public static function getArtisanBinary()
    {
        return defined('ARTISAN_BINARY') ? ProcessUtils::escapeArgument(ARTISAN_BINARY) : 'artisan';
    }

    public static function formatCommandString($string)
    {
        return sprintf('%s %s %s', static::getPhpBinary(), static::getArtisanBinary(), $string);
    }
}
