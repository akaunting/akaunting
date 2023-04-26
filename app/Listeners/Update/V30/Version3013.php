<?php

namespace App\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Version3013 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '3.0.13';

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

        Log::channel('stdout')->info('Updating to 3.0.13 version...');

        Artisan::call('cache:clear');

        Log::channel('stdout')->info('Done!');
    }
}
