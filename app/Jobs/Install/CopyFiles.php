<?php

namespace App\Jobs\Install;

use App\Abstracts\Job;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CopyFiles extends Job
{
    protected $alias;

    protected $path;

    /**
     * Create a new job instance.
     *
     * @param  $alias
     * @param  $path
     */
    public function __construct($alias, $path)
    {
        $this->alias = $alias;
        $this->path = $path;
    }

    /**
     * Execute the job.
     *
     * @return string
     */
    public function handle()
    {
        if (empty($this->path)) {
            throw new \Exception(trans('modules.errors.file_copy', ['module' => $this->alias]));
        }

        $source = storage_path('app/temp/' . $this->path);

        $destination = $this->getDestination($source);

        // Move all files/folders from temp path
        if (!File::copyDirectory($source, $destination)) {
            throw new \Exception(trans('modules.errors.file_copy', ['module' => $this->alias]));
        }

        // Delete temp directory
        File::deleteDirectory($source);
    }

    protected function getDestination($source)
    {
        if ($this->alias == 'core') {
            return base_path();
        }

        if (!is_file($source . '/module.json')) {
            throw new \Exception(trans('modules.errors.file_copy', ['module' => $this->alias]));
        }

        $modules_path = config('module.paths.modules');

        // Create modules directory
        if (!File::isDirectory($modules_path)) {
            File::makeDirectory($modules_path);
        }

        $module_path = $modules_path . '/' . Str::studly($this->alias);

        // Create module directory
        if (!File::isDirectory($module_path)) {
            File::makeDirectory($module_path);
        }

        return $module_path;
    }
}
