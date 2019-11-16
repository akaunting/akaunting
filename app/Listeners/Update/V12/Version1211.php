<?php

namespace App\Listeners\Update\V12;

use App\Events\Install\UpdateFinished as Event;
use App\Listeners\Update\Listener;
use Artisan;

class Version1211 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.2.11';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        // Check if should listen
        if (!$this->check($event)) {
            return;
        }

        // Update database
        Artisan::call('migrate', ['--force' => true]);
    }
}
