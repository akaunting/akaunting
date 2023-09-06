<?php

declare(strict_types=1);

namespace ParaTest\WrapperRunner;

use ParaTest\Options;
use SplFileInfo;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\InputStream;
use Symfony\Component\Process\Process;
use Throwable;

use function array_map;
use function assert;
use function clearstatcache;
use function file_get_contents;
use function filesize;
use function implode;
use function is_string;
use function serialize;
use function sprintf;
use function touch;
use function uniqid;

use const DIRECTORY_SEPARATOR;

/** @internal */
final class WrapperWorker
{
    public const COMMAND_EXIT = "EXIT\n";

    public readonly SplFileInfo $statusFile;
    public readonly SplFileInfo $progressFile;
    public readonly SplFileInfo $unexpectedOutputFile;
    public readonly SplFileInfo $testresultFile;
    public readonly SplFileInfo $junitFile;
    public readonly SplFileInfo $coverageFile;
    public readonly SplFileInfo $teamcityFile;

    public readonly SplFileInfo $testdoxFile;
    private ?string $currentlyExecuting = null;
    private Process $process;
    private int $inExecution = 0;
    private InputStream $input;
    private int $exitCode = -1;

    /** @param non-empty-string[] $parameters */
    public function __construct(
        private readonly OutputInterface $output,
        private readonly Options $options,
        array $parameters,
        private readonly int $token
    ) {
        $commonTmpFilePath = sprintf(
            '%s%sworker_%02s_stdout_%s_',
            $options->tmpDir,
            DIRECTORY_SEPARATOR,
            $token,
            uniqid(),
        );
        $this->statusFile  = new SplFileInfo($commonTmpFilePath . 'status');
        touch($this->statusFile->getPathname());
        $this->progressFile = new SplFileInfo($commonTmpFilePath . 'progress');
        touch($this->progressFile->getPathname());
        $this->unexpectedOutputFile = new SplFileInfo($commonTmpFilePath . 'unexpected_output');
        touch($this->unexpectedOutputFile->getPathname());
        $this->testresultFile = new SplFileInfo($commonTmpFilePath . 'testresult');
        if ($options->configuration->hasLogfileJunit()) {
            $this->junitFile = new SplFileInfo($commonTmpFilePath . 'junit');
        }

        if ($options->configuration->hasCoverageReport()) {
            $this->coverageFile = new SplFileInfo($commonTmpFilePath . 'coverage');
        }

        if ($options->needsTeamcity) {
            $this->teamcityFile = new SplFileInfo($commonTmpFilePath . 'teamcity');
        }

        if ($options->configuration->outputIsTestDox()) {
            $this->testdoxFile = new SplFileInfo($commonTmpFilePath . 'testdox');
        }

        $parameters[] = '--status-file';
        $parameters[] = $this->statusFile->getPathname();
        $parameters[] = '--progress-file';
        $parameters[] = $this->progressFile->getPathname();
        $parameters[] = '--unexpected-output-file';
        $parameters[] = $this->unexpectedOutputFile->getPathname();
        $parameters[] = '--testresult-file';
        $parameters[] = $this->testresultFile->getPathname();
        if (isset($this->teamcityFile)) {
            $parameters[] = '--teamcity-file';
            $parameters[] = $this->teamcityFile->getPathname();
        }

        if (isset($this->testdoxFile)) {
            $parameters[] = '--testdox-file';
            $parameters[] = $this->testdoxFile->getPathname();
            if ($options->configuration->colors()) {
                $parameters[] = '--testdox-color';
            }
        }

        $phpunitArguments = [$options->phpunit];
        foreach ($options->phpunitOptions as $key => $value) {
            if ($options->functional && $key === 'filter') {
                continue;
            }

            $phpunitArguments[] = "--{$key}";
            if ($value === true) {
                continue;
            }

            $phpunitArguments[] = $value;
        }

        $phpunitArguments[] = '--do-not-cache-result';
        $phpunitArguments[] = '--no-logging';
        $phpunitArguments[] = '--no-coverage';
        $phpunitArguments[] = '--no-output';
        if (isset($this->junitFile)) {
            $phpunitArguments[] = '--log-junit';
            $phpunitArguments[] = $this->junitFile->getPathname();
        }

        if (isset($this->coverageFile)) {
            $phpunitArguments[] = '--coverage-php';
            $phpunitArguments[] = $this->coverageFile->getPathname();
        }

        $parameters[] = '--phpunit-argv';
        $parameters[] = serialize($phpunitArguments);

        if ($options->verbose) {
            $output->write(sprintf(
                "Starting process {$this->token}: %s\n",
                implode(' ', array_map('\\escapeshellarg', $parameters)),
            ));
        }

        $this->input   = new InputStream();
        $this->process = new Process(
            $parameters,
            $options->cwd,
            $options->fillEnvWithTokens($token),
            $this->input,
            null,
        );
    }

    public function start(): void
    {
        $this->process->start();
    }

    public function getWorkerCrashedException(?Throwable $previousException = null): WorkerCrashedException
    {
        return WorkerCrashedException::fromProcess(
            $this->process,
            $this->currentlyExecuting ?? 'N.A.',
            $previousException,
        );
    }

    public function assign(string $test): void
    {
        assert($this->currentlyExecuting === null);

        if ($this->options->verbose) {
            $this->output->write("Process {$this->token} executing: {$test}\n");
        }

        $this->input->write($test . "\n");
        $this->currentlyExecuting = $test;
        ++$this->inExecution;
    }

    public function reset(): void
    {
        $this->currentlyExecuting = null;
    }

    public function stop(): void
    {
        $this->input->write(self::COMMAND_EXIT);
    }

    public function isFree(): bool
    {
        $statusFilepath = $this->statusFile->getPathname();
        clearstatcache(true, $statusFilepath);

        $isFree = $this->inExecution === filesize($statusFilepath);

        if ($isFree && $this->inExecution > 0) {
            $exitCodes = file_get_contents($statusFilepath);
            assert(is_string($exitCodes) && $exitCodes !== '');
            $this->exitCode = (int) $exitCodes[-1];
        }

        return $isFree;
    }

    public function getExitCode(): int
    {
        return $this->exitCode;
    }

    public function isRunning(): bool
    {
        return $this->process->isRunning();
    }
}
