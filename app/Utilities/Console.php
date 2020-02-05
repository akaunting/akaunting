<?php

namespace App\Utilities;

use Symfony\Component\Process\Process;

class Console
{
    public static function run($command, $all_output = false, $timeout = 0)
    {
        $process = new Process($command, base_path());
        $process->setTimeout($timeout);

        $process->run();

        if ($process->isSuccessful()) {
            return true;
        }

        return $all_output ? $process->getOutput() : $process->getErrorOutput();
    }
}
