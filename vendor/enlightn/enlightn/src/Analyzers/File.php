<?php

namespace Enlightn\Enlightn\Analyzers;

use SplFileObject;

class File
{
    /**
     * @var \SplFileObject
     */
    private $file;

    public function __construct(string $path)
    {
        $this->file = new SplFileObject($path);
    }

    /**
     * @return int
     */
    public function numberOfLines()
    {
        $this->file->seek(PHP_INT_MAX);

        return $this->file->key() + 1;
    }

    /**
     * @param int|null $lineNumber
     * @return string
     */
    public function getLine(int $lineNumber = null)
    {
        if (is_null($lineNumber)) {
            return $this->getNextLine();
        }

        $this->file->seek($lineNumber - 1);

        return $this->file->current();
    }

    /**
     * @return string
     */
    public function getNextLine()
    {
        $this->file->next();

        return $this->file->current();
    }
}
