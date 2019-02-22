<?php

namespace App\Listeners\Updates\V13;

use App\Events\UpdateFinished;
use App\Listeners\Updates\Listener;
use Date;

class Version138 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.3.8';

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

        // Re-format financial start
        $current_setting = setting('general.financial_start', Date::now()->startOfYear()->format('d F'));

        setting()->setExtraColumns(['company_id' => session('company_id')]);
        setting(['general.financial_start' => Date::parse($current_setting)->format('d-m')]);
        setting()->save();
    }
}
