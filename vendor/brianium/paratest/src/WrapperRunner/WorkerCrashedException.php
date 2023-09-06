<?php

declare(strict_types=1);

namespace ParaTest\WrapperRunner;

use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

use function escapeshellarg;
use function sprintf;

/** @internal */
final class WorkerCrashedException extends RuntimeException
{
    public static function fromProcess(Process $process, string $test, ?Throwable $previousException = null): self
    {
        $envs = '';
        foreach ($process->getEnv() as $key => $value) {
            $envs .= sprintf('%s=%s ', $key, escapeshellarg((string) $value));
        }

        $error = sprintf(
            'The test "%s%s" failed.' . "\n\nExit Code: %s(%s)\n\nWorking directory: %s",
            $envs,
            $test,
            (string) $process->getExitCode(),
            (string) $process->getExitCodeText(),
            (string) $process->getWorkingDirectory(),
        );

        if (! $process->isOutputDisabled()) {
            $error .= sprintf(
                "\n\nOutput:\n================\n%s\n\nError Output:\n================\n%s",
                $process->getOutput(),
                $process->getErrorOutput(),
            );
        }

        return new self($error, 0, $previousException);
    }
}
