<?php

namespace App\Jobs\Common;

use App\Abstracts\JobShouldQueue;
use Illuminate\Support\Facades\File;
use ZipArchive;

class CreateZipForDownload extends JobShouldQueue
{
    public $selected;

    public $class;

    public $file_name;

    /**
     * Create a new job instance.
     *
     * @param  $selected     
     * @param  $class
     * @param  $file_name
     */
    public function __construct($selected, $class, $file_name)
    {
        $this->selected = $selected;
        $this->class = $class;
        $this->file_name = $file_name;

        $this->onQueue('jobs');
    }

    public function handle()
    {
        $zip_archive = new ZipArchive();

        $folder_path = 'app/temp/' . company_id() . '/bulk_actions/';

        File::ensureDirectoryExists(get_storage_path($folder_path));

        $zip_path = get_storage_path($folder_path . $this->file_name . '.zip');

        $zip_archive->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $total = count($this->selected);
        $current = 0;
    
        foreach ($this->selected as $selected) {
            $current++;
    
            if ($current === $total) {
                $this->dispatch(new $this->class($selected, $folder_path, $zip_archive, true));
            } else {
                $this->dispatch(new $this->class($selected, $folder_path, $zip_archive));
            }
        }

        return $zip_path;
    }
}
