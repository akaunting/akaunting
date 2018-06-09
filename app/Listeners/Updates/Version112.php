<?php

namespace App\Listeners\Updates;

use App\Events\UpdateFinished;
use App\Models\Common\Company;
use DotenvEditor;

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
    public function handle(UpdateFinished $event)
    {
        // Check if should listen
        if (!$this->check($event)) {
            return;
        }

        $locale = 'en-GB';

        // Get default locale if only 1 company
        if (Company::all()->count() == 1) {
            $locale = setting('general.default_locale', 'en-GB');
        }

        // Set default locale
        DotenvEditor::setKeys([
            [
                'key'       => 'APP_LOCALE',
                'value'     => $locale,
            ],
        ])->save();
    }
}
