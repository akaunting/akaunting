<?php

namespace App\Listeners\Update\V21;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\Company;
use App\Models\Common\Media;
use App\Utilities\Date;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class Version2112 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.1.12';

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

        $this->updateDatabase();

        $this->updateCompanies();
    }

    public function updateDatabase()
    {
        DB::table('migrations')->insert([
            'id' => DB::table('migrations')->max('id') + 1,
            'migration' => '2016_06_27_000001_create_mediable_test_tables',
            'batch' => DB::table('migrations')->max('batch') + 1,
        ]);

        Artisan::call('migrate', ['--force' => true]);
    }

    public function updateCompanies()
    {
        $companies = Company::withTrashed()->cursor();

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
