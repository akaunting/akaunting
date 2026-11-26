<?php

namespace App\Listeners\Update\V33;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Version333 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '3.3.3';

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

        Log::channel('stdout')->info('Updating to 3.3.3 version...');

        $this->clearCache();

        Log::channel('stdout')->info('Done!');
    }

    /**
     * 3.3.3 changes how the views print their values, which goes into the compiled views. The views
     * the update does not change would keep printing them the old way until they are compiled again.
     */
    public function clearCache(): void
    {
        Log::channel('stdout')->info('Clearing cache...');

        Artisan::call('view:clear');
        Artisan::call('cache:clear');

        Log::channel('stdout')->info('Cleared cache.');
    }
}
