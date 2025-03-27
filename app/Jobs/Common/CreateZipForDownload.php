<?php

namespace App\Jobs\Common;

use App\Abstracts\JobShouldQueue;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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

        $folder_path = 'app' . DIRECTORY_SEPARATOR . 'temp' . DIRECTORY_SEPARATOR . company_id() . DIRECTORY_SEPARATOR . 'bulk_actions' . DIRECTORY_SEPARATOR;

        File::ensureDirectoryExists(storage_path($folder_path));
        File::ensureDirectoryExists(get_storage_path($folder_path));

        $zip_path = storage_path($folder_path . $this->file_name . '.zip');

        $zip_archive->open($zip_path, ZipArchive::CREATE | ZipArchive::OVERWRITE);
    
        foreach ($this->selected as $selected) {
            $pdf_path = $this->dispatch(new $this->class($selected, $folder_path));

            $fileContent = $this->getQueuedFile($pdf_path);
            
            $zip_archive->addFromString(basename($pdf_path), $fileContent);

            /*
            Storage::disk('local')->put($folder_path . basename($pdf_path), $fileContent);

            $zip->addFile(storage_path($folder_path . basename($pdf_path)), basename($pdf_path));
            */
        }

        $zip_archive->close();

        $this->copyQueuedFile($folder_path, $zip_path);

        return $zip_path;
    }

    public function getQueuedFile($pdf_path)
    {
        return config('dompdf.disk') !== null
                ? $this->getRemoteQueuedMedia($pdf_path)
                : $this->getLocalQueuedMedia($pdf_path);
    }

    public function getLocalQueuedMedia($pdf_path)
    {
        $content = File::get($pdf_path);

        return $content;
    }

    public function getRemoteQueuedMedia($pdf_path)
    {
        $disk = config('dompdf.disk');

        $content = Storage::disk($disk)->get($pdf_path);

        return $content;
    }

    public function copyQueuedFile($folder_path, $zip_path)
    {
        return config('dompdf.disk') !== null
                ? $this->copyRemoteQueuedMedia($folder_path, $zip_path)
                : true;
    }

    public function copyRemoteQueuedMedia($folder_path, $zip_path)
    {
        $disk = config('dompdf.disk');

        $file_path = get_storage_path($folder_path . basename($zip_path));

        $content = Storage::disk($disk)->put($file_path, fopen($zip_path, 'r+'));

        report($file_path);

        return $content;
    }
}
