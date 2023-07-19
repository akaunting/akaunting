<?php

namespace App\Traits;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

trait Updates
{
    /**
     * get the alias.
     */
    public function getAlias(): string
    {
        $const = static::class . '::ALIAS';

        return defined($const) ? constant($const) : 'core';
    }

    /**
     * get the path by alias.
     */
    public function getPathByAlias(string $alias = ''): string
    {
        $alias = empty($alias) ? $this->getAlias() : $alias;

        return $alias === 'core' ? base_path() : module_path($alias);
    }

    /**
     * delete the files.
     */
    public function deleteFiles(array $files, $alias = ''): void
    {
        $path = $this->getPathByAlias($alias);

        foreach ($files as $file) {
            File::delete($path . '/' . $file);
        }
    }

    /**
     * delete the folders.
     */
    public function deleteFolders(array $folders, $alias = ''): void
    {
        $path = $this->getPathByAlias($alias);

        foreach ($folders as $folder) {
            File::deleteDirectory($path . '/' . $folder);
        }
    }

    /**
     * Run single migration.
     */
    public function runMigration(string $file_name, $alias = ''): void
    {
        $path = $this->getPathByAlias($alias);

        $semi_path = str($path)->contains('modules') ? '/Database/Migrations/' : '/database/migrations/';

        $migration_file = $path . $semi_path . $file_name . '.php';

        if (File::missing($migration_file)) {
            return;
        }

        $migration_file = str($migration_file)->replace(base_path(), '');

        Artisan::call('migrate', ['--force' => true, '--path' => $migration_file]);
    }

    /**
     * Run multiple migrations.
     */
    public function runMigrations(array $file_names, $alias = ''): void
    {
        foreach ($file_names as $file_name) {
            $this->runMigration($file_name, $alias);
        }
    }
}
