<?php

namespace App\Listeners\Update\V10;

use App\Events\Install\UpdateFinished as Event;
use App\Listeners\Update\Listener;
use DB;

class Version107 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.0.7';

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

        $table = env('DB_PREFIX') . 'taxes';

        DB::statement("ALTER TABLE `$table` MODIFY `rate` DOUBLE(15,4) NOT NULL");
    }
}
