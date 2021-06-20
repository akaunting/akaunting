<?php

namespace App\Jobs\Common;

use App\Abstracts\JobShouldQueue;
use App\Models\Common\Media as MediaModel;
use App\Notifications\Common\ExportCompleted;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use MediaUploader;

class CreateMediableForExport extends JobShouldQueue
{
    protected $user;

    protected $file_name;

    protected $translation;

    /**
     * Create a new job instance.
     *
     * @param  $user
     * @param  $file_name
     */
    public function __construct($user, $file_name, $translation)
    {
        $this->user = $user;
        $this->file_name = $file_name;
        $this->translation = $translation;

        $this->onQueue('jobs');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $media = $this->getQueuedMedia();

        $this->user->attachMedia($media, 'export');

        $download_url = route('uploads.download', ['id' => $media->id, 'company_id' => company_id()]);

        $this->user->notify(new ExportCompleted($this->translation, $this->file_name, $download_url));
    }

    public function getQueuedMedia()
    {
        return config('excel.temporary_files.remote_disk') !== null
                ? $this->getRemoteQueuedMedia()
                : $this->getLocalQueuedMedia();
    }

    public function getLocalQueuedMedia()
    {
        $source = storage_path('app/temp/' . $this->file_name);

        $destination = $this->getMediaFolder('exports');

        $media = MediaUploader::makePrivate()
                        ->beforeSave(function(MediaModel $media) {
                            $media->company_id = company_id();
                        })
                        ->fromSource($source)
                        ->toDirectory($destination)
                        ->upload();

        File::delete($source);

        return $media;
    }

    public function getRemoteQueuedMedia()
    {
        $disk = config('excel.temporary_files.remote_disk');
        $prefix = config('excel.temporary_files.remote_prefix');

        $content = Storage::disk($disk)->get($this->file_name);

        $file_name = str_replace([$prefix, '.xlsx', '.xls'], '', $this->file_name);

        $destination = $this->getMediaFolder('exports');

        $media = MediaUploader::makePrivate()
                        ->beforeSave(function(MediaModel $media) {
                            $media->company_id = company_id();
                        })
                        ->fromString($content)
                        ->useFilename($file_name)
                        ->toDirectory($destination)
                        ->upload();

        Storage::disk($disk)->delete($this->file_name);

        return $media;
    }
}
