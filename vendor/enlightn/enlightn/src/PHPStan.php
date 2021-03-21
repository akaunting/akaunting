<?php

namespace Enlightn\Enlightn;

use Enlightn\Enlightn\Analyzers\Trace;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class PHPStan
{
    /**
     * The PHPStan analysis result.
     *
     * @var array
     */
    public $result;

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The project root path.
     *
     * @var string|null
     */
    protected $rootPath;

    /**
     * The PHPStan configuration file path.
     *
     * @var string|null
     */
    protected $configPath;

    /**
     * Create a new PHPStan manager instance.
     *
     * @param  \Illuminate\Filesystem\Filesystem  $files
     * @param  string|null  $rootPath
     * @return void
     */
    public function __construct(Filesystem $files, $rootPath = null)
    {
        $this->files = $files;
        $this->rootPath = $rootPath;
    }

    /**
     * Parse the PHPStan analysis and get the results containing the search string.
     *
     * @param string|array $search
     * @return array
     */
    public function parseAnalysis($search)
    {
        if (! isset($this->result['files'])) {
            return [];
        }

        return collect($this->result['files'])->map(function ($fileAnalysis, $path) use ($search) {
            return collect($fileAnalysis['messages'])->filter(function ($message) use ($search) {
                return Str::contains($message['message'], $search);
            })->map(function ($message) use ($path) {
                return new Trace($path, $message['line'], $message['message']);
            })->flatten()->toArray();
        })->filter()->flatten()->toArray();
    }

    /**
     * Parse the PHPStan analysis and get the results matching the pattern.
     *
     * @param  string|array  $pattern
     * @return array
     */
    public function match($pattern)
    {
        if (! isset($this->result['files'])) {
            return [];
        }

        return collect($this->result['files'])->map(function ($fileAnalysis, $path) use ($pattern) {
            return collect($fileAnalysis['messages'])->filter(function ($message) use ($pattern) {
                return Str::is($pattern, $message['message']);
            })->map(function ($message) use ($path) {
                return new Trace($path, $message['line'], $message['message']);
            })->flatten()->toArray();
        })->filter()->flatten()->toArray();
    }

    /**
     * Run the PHPStan analysis and get the output
     *
     * @param  string|array  $paths
     * @param  string|null  $configPath
     * @return $this
     */
    public function start($paths, $configPath = null)
    {
        $configPath = $configPath ?? $this->configPath ?? (__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'phpstan.neon');

        $options = ['analyse', '--configuration='.$configPath, '--error-format=json', '--no-progress'];

        foreach (Arr::wrap($paths) as $path) {
            $options[] = $path;
        }

        $this->result = json_decode($this->runCommand($options, false), true);

        return $this;
    }

    /**
     * Run any PHPStan command and get the output
     *
     * @param  array  $options
     * @param  bool  $includeErrorOutput
     * @return string
     */
    public function runCommand(array $options = [], $includeErrorOutput = true)
    {
        $phpStan = $this->findPHPStan();

        $command = array_merge((array) $phpStan, $options);

        $process = $this->getProcess($command);

        $process->run();

        return $process->getOutput().($includeErrorOutput ? $process->getErrorOutput() : '');
    }

    /**
     * Set the PHPStan configuration file path.
     *
     * @param string $configPath
     * @return $this
     */
    public function setConfigPath(string $configPath)
    {
        $this->configPath = $configPath;

        return $this;
    }

    /**
     * Get the PHPStan command for the environment.
     *
     * @return array
     */
    protected function findPHPStan()
    {
        return [$this->rootPath.'/vendor/bin/phpstan'];
    }

    /**
     * Get a new Symfony process instance.
     *
     * @param  array  $command
     * @return \Symfony\Component\Process\Process
     */
    protected function getProcess(array $command)
    {
        return (new Process($command, $this->rootPath))->setTimeout(null);
    }

    /**
     * Set the root path used by the class.
     *
     * @param  string  $path
     * @return $this
     */
    public function setRootPath(string $path)
    {
        $this->rootPath = realpath($path);

        return $this;
    }
}
