<?php

namespace Spatie\FlareClient\FlareMiddleware;

use Closure;
use Spatie\FlareClient\Report;
use Symfony\Component\Process\Process;
use Throwable;

class AddGitInformation
{
    protected ?string $baseDir = null;

    public function handle(Report $report, Closure $next)
    {
        try {
            $this->baseDir = $this->getGitBaseDirectory();

            if (! $this->baseDir) {
                return $next($report);
            }

            $report->group('git', [
                'hash' => $this->hash(),
                'message' => $this->message(),
                'tag' => $this->tag(),
                'remote' => $this->remote(),
                'isDirty' => ! $this->isClean(),
            ]);
        } catch (Throwable) {
        }

        return $next($report);
    }

    protected function hash(): ?string
    {
        return $this->command("git log --pretty=format:'%H' -n 1") ?: null;
    }

    protected function message(): ?string
    {
        return $this->command("git log --pretty=format:'%s' -n 1") ?: null;
    }

    protected function tag(): ?string
    {
        return $this->command('git describe --tags --abbrev=0') ?: null;
    }

    protected function remote(): ?string
    {
        return $this->command('git config --get remote.origin.url') ?: null;
    }

    protected function isClean(): bool
    {
        return empty($this->command('git status -s'));
    }

    protected function getGitBaseDirectory(): ?string
    {
        /** @var Process $process */
        $process = Process::fromShellCommandline("echo $(git rev-parse --show-toplevel)")->setTimeout(1);

        $process->run();

        if (! $process->isSuccessful()) {
            return null;
        }

        $directory = trim($process->getOutput());

        if (! file_exists($directory)) {
            return null;
        }

        return $directory;
    }

    protected function command($command)
    {
        $process = Process::fromShellCommandline($command, $this->baseDir)->setTimeout(1);

        $process->run();

        return trim($process->getOutput());
    }
}
