<?php

namespace App\Listeners\Updates\V12;

use App\Events\UpdateFinished;
use App\Listeners\Updates\Listener;
use Artisan;

class Version1210 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.2.10';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        // Check if should listen
        if (!$this->check($event)) {
            return;
        }

        // Update database
        Artisan::call('migrate', ['--force' => true]);
    }
}
