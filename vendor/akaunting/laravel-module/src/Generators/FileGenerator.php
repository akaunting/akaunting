<?php

namespace Akaunting\Module\Generators;

use Illuminate\Filesystem\Filesystem;
use Akaunting\Module\Exceptions\FileAlreadyExistException;

class FileGenerator extends Generator
{
    /**
     * The path wil be used.
     *
     * @var string
     */
    protected $path;

    /**
     * The contens will be used.
     *
     * @var string
     */
    protected $contents;

    /**
     * The laravel filesystem or null.
     *
     * @var \Illuminate\Filesystem\Filesystem|null
     */
    protected $filesystem;

    /**
     * The constructor.
     *
     * @param $path
     * @param $contents
     * @param null $filesystem
     */
    public function __construct($path, $contents, $filesystem = null)
    {
        $this->path = $path;
        $this->contents = $contents;
        $this->filesystem = $filesystem ?: new Filesystem();
    }

    /**
     * Get contents.
     *
     * @return mixed
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * Set contents.
     *
     * @param mixed $contents
     *
     * @return $this
     */
    public function setContents($contents)
    {
        $this->contents = $contents;

        return $this;
    }

    /**
     * Get filesystem.
     *
     * @return mixed
     */
    public function getFilesystem()
    {
        return $this->filesystem;
    }

    /**
     * Set filesystem.
     *
     * @param Filesystem $filesystem
     *
     * @return $this
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        return $this;
    }

    /**
     * Get path.
     *
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path.
     *
     * @param mixed $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function withFileOverwrite(bool $overwrite): FileGenerator
    {
        $this->overwriteFile = $overwrite;

        return $this;
    }

    /**
     * Generate the file.
     */
    public function generate()
    {
        $path = $this->getPath();

        if (!$this->filesystem->exists($path)) {
            return $this->filesystem->put($path, $this->getContents());
        }

        if ($this->overwriteFile === true) {
            return $this->filesystem->put($path, $this->getContents());
        }

        throw new FileAlreadyExistException('File already exists!');
    }
}
