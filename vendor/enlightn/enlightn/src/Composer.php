<?php

namespace Enlightn\Enlightn;

use Illuminate\Support\Composer as BaseComposer;

class Composer extends BaseComposer
{
    /**
      * Get the package dependencies.
      *
      * @param  bool  $includeDev
      * @return array
      */
    public function getDependencies($includeDev = true)
    {
        $arguments = ['show', '-N'];

        if (! $includeDev) {
            $arguments[] = '--no-dev';
        }

        return array_map(
            'trim',
            array_filter(explode("\n", $this->runCommand($arguments, false)))
        );
    }

    /**
     * Get the licenses of all packages used by the application.
     *
     * @param  bool  $excludeDev
     * @return \Illuminate\Support\Collection
     */
    public function getLicenses($excludeDev = true)
    {
        $arguments = ['licenses', '--format=json'];

        if ($excludeDev) {
            $arguments[] = '--no-dev';
        }

        return collect(json_decode($this->runCommand($arguments, false), true)['dependencies'] ?? [])
            ->map(function ($value) {
                return $value['license'];
            });
    }

    /**
     * Run a dry run Composer install.
     *
     * @param  array  $options
     * @return string
     */
    public function installDryRun(array $options = [])
    {
        return $this->runCommand(array_merge(['install', '--dry-run'], $options));
    }

    /**
     * Run a dry run Composer update.
     *
     * @param  array  $options
     * @return string
     */
    public function updateDryRun(array $options = [])
    {
        return $this->runCommand(array_merge(['update', '--dry-run'], $options));
    }

    /**
     * Run any Composer command and get the output.
     *
     * @param  array  $options
     * @param  bool  $includeErrorOutput
     * @return string
     */
    public function runCommand(array $options = [], $includeErrorOutput = true)
    {
        $composer = $this->findComposer();

        $command = array_merge(
            (array) $composer,
            $options
        );

        $process = $this->getProcess($command);

        $process->run();

        return $process->getOutput().($includeErrorOutput ? $process->getErrorOutput() : '');
    }

    /**
     * Get the composer lock file location.
     *
     * @return string|null
     */
    public function getLockFile()
    {
        if ($this->files->exists($this->workingPath.'/composer.lock')) {
            return $this->workingPath.'/composer.lock';
        }

        return null;
    }

    /**
     * Get the composer lock file location.
     *
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function getJson()
    {
        return json_decode($this->files->get($this->getJsonFile()), true);
    }

    /**
     * Get the composer lock file location.
     *
     * @return string|null
     */
    public function getJsonFile()
    {
        if ($this->files->exists($this->workingPath.'/composer.json')) {
            return $this->workingPath.'/composer.json';
        }

        return null;
    }
}
