<?php

namespace App\Utilities;

use Illuminate\Console\Application;
use Symfony\Component\Process\Process;

class Console
{
    public static function run($string, $all_output = false, $timeout = 0)
    {
        $command = Application::formatCommandString($string);

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
}
