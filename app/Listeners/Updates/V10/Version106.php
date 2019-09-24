<?php

namespace App\Listeners\Updates\V10;

use App\Events\UpdateFinished;
use App\Listeners\Updates\Listener;
use File;

class Version106 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.0.6';

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

        // Moved to app directory
        File::deleteDirectory(app_path('Http' . DIRECTORY_SEPARATOR .'Transformers'));
    }
}
