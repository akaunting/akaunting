<?php

namespace App\Listeners\Update\V10;

use App\Events\Install\UpdateFinished as Event;
use App\Listeners\Update\Listener;
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
    public function handle(Event $event)
    {
        // Check if should listen
        if (!$this->check($event)) {
            return;
        }

        // Moved to app directory
        File::deleteDirectory(app_path('Http' . DIRECTORY_SEPARATOR .'Transformers'));
    }
}
