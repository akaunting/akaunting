<?php

namespace App\Jobs\Install;

use App\Abstracts\Job;
use Illuminate\Support\Facades\File;
use ZipArchive;

class UnzipFile extends Job
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
            throw new \Exception(trans('modules.errors.unzip', ['module' => $this->alias]));
        }

        $temp_path = storage_path('app/temp/' . $this->path);

        $file = $temp_path . '/upload.zip';

        // Unzip the file
        $zip = new ZipArchive();

        if (!$zip->open($file) || !$zip->extractTo($temp_path)) {
            throw new \Exception(trans('modules.errors.unzip', ['module' => $this->alias]));
        }

        $zip->close();

        // Remove Zip
        File::delete($file);

        return $this->path;
    }
}
