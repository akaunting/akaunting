<?php

namespace App\Listeners\Update\V21;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\Company;
use App\Models\Common\Media;
use App\Utilities\Date;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Version2111 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.1.11';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateCompanies();
    }

    public function updateCompanies()
    {
        $companies = Company::cursor();

        foreach ($companies as $company) {
            $this->moveMedia($company);
        }
    }

    public function moveMedia($company)
    {
        $medias = Media::inDirectory('uploads', $company->id . '/', true)->cursor();

        foreach ($medias as $media) {
            // Bizarre record
            if (empty($media->directory) || empty($media->basename)) {
                $media->delete();

                continue;
            }

            // Delete media from db if file not exists
            if (!Storage::exists($media->directory . '/' . $media->basename)) {
                $media->delete();

                continue;
            }

            // Delete completely if soft deleted
            if (!empty($media->deleted_at)) {
                $media->delete();

                Storage::delete($media->directory . '/' . $media->basename);

                continue;
            }

            $date = Date::parse($media->created_at)->format('Y/m/d');

            $new_folder = $date . '/'. $media->directory;

            // Check if already exists and delete
            if (Storage::exists($new_folder . '/' . $media->basename)) {
                Storage::delete($new_folder . '/' . $media->basename);
            }

            $media->move($new_folder);
        }

        // Delete old company folder
        File::deleteDirectory(Storage::path($company->id));
    }
}
