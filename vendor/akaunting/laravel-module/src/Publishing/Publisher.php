<?php

namespace Akaunting\Module\Publishing;

use Illuminate\Console\Command;
use Akaunting\Module\Contracts\PublisherInterface;
use Akaunting\Module\Contracts\RepositoryInterface;
use Akaunting\Module\Module;

abstract class Publisher implements PublisherInterface
{
    /**
     * The name of module will used.
     *
     * @var string
     */
    protected $module;

    /**
     * The modules repository instance.
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * The laravel console instance.
     *
     * @var \Illuminate\Console\Command
     */
    protected $console;

    /**
     * The success message will displayed at console.
     *
     * @var string
     */
    protected $success;

    /**
     * The error message will displayed at console.
     *
     * @var string
     */
    protected $error = '';

    /**
     * Determine whether the result message will shown in the console.
     *
     * @var bool
     */
    protected $showMessage = true;

    /**
     * The constructor.
     *
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * Show the result message.
     *
     * @return self
     */
    public function showMessage()
    {
        $this->showMessage = true;

        return $this;
    }

    /**
     * Hide the result message.
     *
     * @return self
     */
    public function hideMessage()
    {
        $this->showMessage = false;

        return $this;
    }

    /**
     * Get module instance.
     *
     * @return \Akaunting\Module\Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * Set modules repository instance.
     * @param RepositoryInterface $repository
     * @return $this
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;

        return $this;
    }

    /**
     * Get modules repository instance.
     *
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Set console instance.
     *
     * @param \Illuminate\Console\Command $console
     *
     * @return $this
     */
    public function setConsole(Command $console)
    {
        $this->console = $console;

        return $this;
    }

    /**
     * Get console instance.
     *
     * @return \Illuminate\Console\Command
     */
    public function getConsole()
    {
        return $this->console;
    }

    /**
     * Get laravel filesystem instance.
     *
     * @return \Illuminate\Filesystem\Filesystem
     */
    public function getFilesystem()
    {
        return $this->repository->getFiles();
    }

    /**
     * Get destination path.
     *
     * @return string
     */
    abstract public function getDestinationPath();

    /**
     * Get source path.
     *
     * @return string
     */
    abstract public function getSourcePath();

    /**
     * Publish something.
     */
    public function publish()
    {
        if (!$this->console instanceof Command) {
            $message = "The 'console' property must instance of \\Illuminate\\Console\\Command.";

            throw new \RuntimeException($message);
        }

        if (!$this->getFilesystem()->isDirectory($sourcePath = $this->getSourcePath())) {
            return;
        }

        if (!$this->getFilesystem()->isDirectory($destinationPath = $this->getDestinationPath())) {
            $this->getFilesystem()->makeDirectory($destinationPath, 0775, true);
        }

        if ($this->getFilesystem()->copyDirectory($sourcePath, $destinationPath)) {
            if ($this->showMessage === true) {
                $this->console->line("<info>Published</info>: {$this->module->getAlias()}");
            }
        } else {
            $this->console->error($this->error);
        }
    }
}
