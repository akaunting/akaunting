<?php

namespace App\Listeners\Updates\V10;

use App\Events\UpdateFinished;
use App\Listeners\Updates\Listener;
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
    public function handle(UpdateFinished $event)
    {
        // Check if should listen
        if (!$this->check($event)) {
            return;
        }

        $table = env('DB_PREFIX') . 'taxes';

        DB::statement("ALTER TABLE `$table` MODIFY `rate` DOUBLE(15,4) NOT NULL");
    }
}
