<?php

namespace App\Listeners\Updates;

use App\Events\UpdateFinished;
use Date;

class Version135 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.3.5';

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

        // Add financial year start to settings
        setting(['general.financial_start' => Date::now()->startOfYear()->format('d F')]);
        setting()->save();
    }
}
