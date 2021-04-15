<?php

namespace App\Jobs\Common;

use App\Abstracts\JobShouldQueue;
use App\Notifications\Common\ExportCompleted;
use Illuminate\Support\Facades\File;

class CreateMediableForExport extends JobShouldQueue
{
    protected $user;

    protected $file_name;

    /**
     * Create a new job instance.
     *
     * @param  $user
     * @param  $file_name
     */
    public function __construct($user, $file_name)
    {
        $this->user = $user;
        $this->file_name = $file_name;

        $this->onQueue('jobs');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $source = storage_path('app/uploads/' . $this->file_name);
        $destination = storage_path('app/uploads/' . company_id() . '/exports/');

        // Create exports directory
        if (!File::isDirectory($destination)) {
            File::makeDirectory($destination);
        }

        File::move($source, $destination . $this->file_name);

        // Create the media record
        $media = $this->importMedia($this->file_name, 'exports');

        $this->user->attachMedia($media, 'export');

        $download_url = route('uploads.download', ['id' => $media->id, 'company_id' => company_id()]);

        $this->user->notify(new ExportCompleted($download_url));
    }
}
