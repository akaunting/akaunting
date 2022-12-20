<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait Updates
{
    /**
     * get the path by alias.
     */
    public function getPathByAlias(string $alias = ''): string
    {
        if (empty($alias)) {
            $const = static::class . '::ALIAS';

            $alias = defined($const) ? constant($const) : 'core';
        }

        return $alias === 'core' ? base_path() : module_path($alias);
    }

    /**
     * delete the files.
     */
    public function deleteFiles(array $files, $alias = null): void
    {
        $path = $this->getPathByAlias($alias);

        foreach ($files as $file) {
            File::delete($path . '/' . $file);
        }
    }

    /**
     * delete the folders.
     */
    public function deleteFolders(array $folders, $alias = null): void
    {
        $path = $this->getPathByAlias($alias);

        foreach ($folders as $folder) {
            File::deleteDirectory($path . '/' . $folder);
        }
    }
}
