<?php

namespace App\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class Version304 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '3.0.4';

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

        Log::channel('stderr')->info('Starting the Akaunting 3.0.4 update...');

        $this->updateDatabase();

        $this->deleteOldFiles();

        Log::channel('stderr')->info('Akaunting 3.0.4 update finished.');
    }

    public function updateDatabase()
    {
        Log::channel('stderr')->info('Updating database...');

        DB::table('migrations')->insert([
            'id' => DB::table('migrations')->max('id') + 1,
            'migration' => '2022_06_28_000000_core_v304',
            'batch' => DB::table('migrations')->max('batch') + 1,
        ]);

        Artisan::call('migrate', ['--force' => true]);

        Log::channel('stderr')->info('Database updated.');
    }

    public function deleteOldFiles()
    {
        Log::channel('stderr')->info('Deleting old files...');

        $files = [
            'app/Events/Auth/InvitationCreated.php',
            'app/Listeners/Auth/SendUserInvitation.php',
            'app/Listeners/Auth/DeleteUserInvitation.php',
        ];

        foreach ($files as $file) {
            File::delete(base_path($file));
        }

        Log::channel('stderr')->info('Old files deleted.');
    }
}
