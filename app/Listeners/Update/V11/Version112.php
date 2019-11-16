<?php

namespace App\Listeners\Update\V11;

use App\Events\Install\UpdateFinished as Event;
use App\Listeners\Update\Listener;
use App\Models\Common\Company;
use App\Utilities\Installer;

class Version112 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.1.2';

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

        $locale = 'en-GB';

        // Get default locale if only 1 company
        if (Company::all()->count() == 1) {
            $locale = setting('default.locale', 'en-GB');
        }

        // Set default locale
        Installer::updateEnv(['APP_LOCALE' => $locale]);
    }
}
