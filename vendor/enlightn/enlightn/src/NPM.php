<?php

namespace Enlightn\Enlightn;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class NPM
{
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
     * Determine whether the command is npm or yarn.
     *
     * @var bool
     */
    protected $isYarn = false;

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
     * Count the frontend vulnerabilities.
     *
     * @param bool $excludeDev
     * @return int
     */
    public function countVulnerabilities($excludeDev = true)
    {
        $auditResult = $this->audit($excludeDev);

        if (empty($auditResult)) {
            return 0;
        }

        return collect($auditResult['metadata']['vulnerabilities']
            ?? $auditResult['data']['vulnerabilities'] ?? [])
            ->filter(function ($_, $type) {
                return ! in_array($type, ['total', 'info']);
            })->sum();
    }

    /**
     * Run the NPM audit command and get the vulnerabilities.
     *
     * @param bool $excludeDev
     * @return array
     */
    public function audit($excludeDev = true)
    {
        $options = ($excludeDev && ! $this->isYarn) ? ['audit', '--production', '--json'] : ['audit', '--json'];

        return json_decode($this->runCommand($options, true), true) ?? [];
    }

    /**
     * Run any npm or yarn command and get the output.
     *
     * @param  array  $options
     * @param  bool  $includeErrorOutput
     * @return string|null
     */
    public function runCommand(array $options = [], $includeErrorOutput = true)
    {
        $npm = $this->findNpmOrYarn();

        if (empty($npm)) {
            return null;
        }

        $command = array_merge((array) $npm, $options);

        $process = $this->getProcess($command);

        $process->run();

        return $process->getOutput().($includeErrorOutput ? $process->getErrorOutput() : '');
    }

    /**
     * Get the npm or yarn command for the environment.
     *
     * @return array
     */
    public function findNpmOrYarn()
    {
        if (! $this->files->exists($this->rootPath.'/package.json')) {
            return [];
        }

        if ($this->commandExists('npm')) {
            return ['npm'];
        }

        if ($this->commandExists('yarn')) {
            $this->isYarn = false;

            return ['yarn'];
        }

        return [];
    }

    /**
     * Get the npm or yarn command for the environment.
     *
     * @param string $command
     * @return bool
     */
    protected function commandExists(string $command)
    {
        return is_executable(trim(shell_exec(
            strpos(PHP_OS, 'WIN') === 0 ? 'where ' : 'command -v '
            .$command
        )));
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
