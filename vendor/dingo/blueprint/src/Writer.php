<?php

namespace Dingo\Blueprint;

use Illuminate\Filesystem\Filesystem;

class Writer
{
    /**
     * Filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * Create a new writer instance.
     *
     * @param \Illuminate\Filesystem\Filesystem $files
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    /**
     * Write the contents to the given file path.
     *
     * @param string $contents
     * @param string $file
     *
     * @return void
     */
    public function write($contents, $file)
    {
        $this->files->put($file, $contents);
    }
}
