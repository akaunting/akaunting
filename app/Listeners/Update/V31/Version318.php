<?php

namespace App\Listeners\Update\V31;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Traits\Permissions;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class Version318 extends Listener
{
    use Permissions;

    const ALIAS = 'core';

    const VERSION = '3.1.8';

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

        Log::channel('stdout')->info('Updating to 3.1.8 version...');

        $this->deleteOldFiles();

        $this->clearCache();

        $this->updatePermissions();

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

    public function updatePermissions()
    {
        // c=create, r=read, u=update, d=delete
        $this->attachPermissionsToAdminRoles([
            'offline-payments-settings' => 'r,u,d',
        ]);
    }
}
