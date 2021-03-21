<?php

namespace Enlightn\Enlightn;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem as BaseFilesystem;
use Illuminate\Support\LazyCollection;
use SplFileObject;

class Filesystem extends BaseFilesystem
{
    // Port of Illuminate Filesystem lines method for Laravel 6-7

    /**
     * Get the contents of a file one line at a time.
     *
     * @param  string  $path
     * @return \Illuminate\Support\LazyCollection
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function lines($path)
    {
        if (! $this->isFile($path)) {
            throw new FileNotFoundException(
                "File does not exist at path {$path}."
            );
        }

        return LazyCollection::make(function () use ($path) {
            $file = new SplFileObject($path);

            $file->setFlags(SplFileObject::DROP_NEW_LINE);

            while (! $file->eof()) {
                yield $file->fgets();
            }
        });
    }
}
