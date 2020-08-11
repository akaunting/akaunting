<?php

namespace App\Utilities;

use Illuminate\Console\Application;
use Illuminate\Support\Str;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\LogicException;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\Process;

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
        } catch (InvalidArgumentException | LogicException | ProcessFailedException | RuntimeException $e) {
            $output = $e->getMessage();
        }

        logger('Console output:: ' . $output);

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
