<?php

namespace App\Listeners\Update\V21;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;

class Version2126 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.1.26';

    /**
     * Handle the event.
     *
     * @param  $event
     *
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $country_code = array_search(setting('company.country'), trans('countries'));

        if ($country_code) {
            setting()->set('company.country', $country_code);
            setting()->save();
        }
    }
}