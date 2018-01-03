<?php

namespace App\Listeners\Updates;

use App\Events\UpdateFinished;
use App\Models\Setting\Currency;

class Version116 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.1.6';

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

    }
}
