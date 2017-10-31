<?php

namespace App\Listeners\Updates;

use App\Events\UpdateFinished;

class Version108 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.0.8';

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

        setting(['general.invoice_number_prefix' => setting('general.invoice_prefix', 'INV-')]);
        setting(['general.invoice_number_digit' => setting('general.invoice_digit', '5')]);
        setting(['general.invoice_number_next' => setting('general.invoice_start', '1')]);

        setting()->forget('general.invoice_prefix');
        setting()->forget('general.invoice_digit');
        setting()->forget('general.invoice_start');

        setting()->save();
    }
}
