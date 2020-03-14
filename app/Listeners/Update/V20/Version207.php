<?php

namespace App\Listeners\Update\V20;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Utilities\Installer;

class Version207 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.0.7';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        // Update .env file
        Installer::updateEnv([
            'MAIL_MAILER'       =>  env('MAIL_DRIVER'),
        ]);
    }
}
