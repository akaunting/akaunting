<?php

namespace App\Listeners\Update\V32;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class Version322 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '3.2.2';

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

        Log::channel('stdout')->info('Updating to 3.2.2 version...');

        $this->deleteOldFiles();

        Log::channel('stdout')->info('Done!');
    }

    public function deleteOldFiles(): void
    {
        Log::channel('stdout')->info('Deleting old files and folders...');

        $files = [
            'oauth-keys/oauth-private.key',
            'oauth-keys/oauth-public.key',
        ];

        $directories = [
            'oauth-keys',
        ];

        Log::channel('stdout')->info('Deleting old files...');

        foreach ($files as $file) {
            File::delete(storage_path($file));
        }

        Log::channel('stdout')->info('Deleting old folders...');

        foreach ($directories as $directory) {
            File::deleteDirectory(storage_path($directory));
        }

        Log::channel('stdout')->info('Old files and folders deleted.');
    }
}
