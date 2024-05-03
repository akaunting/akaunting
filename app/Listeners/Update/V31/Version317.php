<?php

namespace App\Listeners\Update\V31;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class Version317 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '3.1.7';

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

        Log::channel('stdout')->info('Updating to 3.1.7 version...');

        $this->deleteOldFiles();

        $this->clearCache();

        Log::channel('stdout')->info('Done!');
    }

    public function clearCache(): void
    {
        Log::channel('stdout')->info('Clearing cache...');

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        Log::channel('stdout')->info('Cleared cache.');
    }

    public function deleteOldFiles()
    {
        Log::channel('stdout')->info('Deleting old files and folders...');

        $files = [
            'public/vendor/alpinejs/alpine.min.js',
            'public/vendor/livewire/livewire.js',
            'public/vendor/livewire/livewire.js.map',
            'public/vendor/livewire/manifest.json',
        ];

        $directories = [
            'public/vendor/alpinejs',
            'public/vendor/livewire',
        ];

        Log::channel('stdout')->info('Deleting old files...');

        foreach ($files as $file) {
            File::delete(base_path($file));
        }

        Log::channel('stdout')->info('Deleting old folders...');

        foreach ($directories as $directory) {
            File::deleteDirectory(base_path($directory));
        }

        Log::channel('stdout')->info('Old files and folders deleted.');
    }
}
