<?php

namespace App\Listeners\Updates;

use App\Events\UpdateFinished;
use Artisan;

class Version130 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.3.0';

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

        // Set new Item Reminder settings
        setting(['general.send_item_reminder' => '0');
        setting(['general.schedule_item_stocks' => '3,5,7']);

        setting()->save();
    }
}
