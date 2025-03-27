<?php

namespace App\Jobs\Common;

use App\Abstracts\JobShouldQueue;
use App\Models\Common\Media as MediaModel;
use App\Notifications\Common\DownloadCompleted;
use App\Notifications\Common\DownloadFailed;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use MediaUploader;

class CreateMediableForDownload extends JobShouldQueue
{
    protected $user;

    protected $file_name;

    protected $translation;

    /**
     * Create a new job instance.
     *
     * @param  $user
     * @param  $file_name
     * @param  $translation
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
        try {
            $media = $this->getQueuedMedia();

            $this->user->attachMedia($media, 'download');
    
            $download_url = route('uploads.download', ['id' => $media->id, 'company_id' => company_id()]);
    
            $this->user->notify(new DownloadCompleted($this->translation, $this->file_name, $download_url));
        } catch (\Throwable $exception) {
            report($exception);

            $this->user->notify(new DownloadFailed($exception->getMessage()));

            throw $exception;
        }
    }

    public function getQueuedMedia()
    {
        return config('dompdf.disk') !== null
                ? $this->getRemoteQueuedMedia()
                : $this->getLocalQueuedMedia();
    }

    public function getLocalQueuedMedia()
    {
        $folder_path = 'app/temp/' . company_id() . '/bulk_actions/';

        $source = storage_path($folder_path . $this->file_name . '.zip');

        $destination = $this->getMediaFolder('bulk_actions');

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
        $disk = config('dompdf.disk');

        $folder_path = 'app/temp/' . company_id() . '/bulk_actions/';

        $source = get_storage_path($folder_path . $this->file_name . '.zip');

        $content = Storage::disk($disk)->get($source);

        $file_name = str_replace(['.pdf', '.zip'], '', $this->file_name);

        $destination = $this->getMediaFolder('bulk_actions');

        $media = MediaUploader::makePrivate()
                        ->beforeSave(function(MediaModel $media) {
                            $media->company_id = company_id();
                        })
                        ->fromString($content)
                        ->useFilename($file_name)
                        ->toDirectory($destination)
                        ->upload();

        Storage::disk($disk)->delete($source);

        return $media;
    }
}
