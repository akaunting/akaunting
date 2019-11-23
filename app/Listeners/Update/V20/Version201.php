<?php

namespace App\Listeners\Update\V13;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Artisan;

class Version201 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.0.1';

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

        // Update database
        Artisan::call('migrate', ['--force' => true]);
    }
}
