<?php

namespace App\Listeners\Update\V31;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Version3112 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '3.1.12';

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

        Log::channel('stdout')->info('Updating to 3.1.12 version...');

        $this->updateDatabase();

        Log::channel('stdout')->info('Done!');
    }

    public function updateDatabase(): void
    {
        Log::channel('stdout')->info('Updating database...');

        Artisan::call('migrate', ['--force' => true]);

        Log::channel('stdout')->info('Database updated.');
    }
}
