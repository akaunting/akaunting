<?php

namespace App\Utilities;

use Exception;
use Illuminate\Console\Application;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

class Console
{
    public static function run($string, $timeout = 0)
    {
        $command = Application::formatCommandString($string);

        logger('Console command:: ' . $command);

        try {
            $process = Process::fromShellCommandline($command, base_path());
            $process->setTimeout($timeout);

            $process->mustRun();

            $output = $process->getOutput();

            if (static::isValidOutput($output)) {
                return true;
            }
        } catch (Exception | InvalidArgumentException | LogicException | ProcessFailedException | RuntimeException | Throwable $e) {
            $output = $e->getMessage();
        }

        logger('Console output:: ' . $output);

        return static::formatOutput($output);
    }

    public static function formatOutput($output)
    {
        $output = nl2br($output);
        $output = str_replace(['"', "'"], '', $output);
        $output = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $output);

        return $output;
    }

    public static function isValidOutput($output)
    {
        $errors = [
            'Content-Type: application/json',
            'CSRF token mismatch',
        ];

        return !Str::contains($output, $errors);
    }
}
