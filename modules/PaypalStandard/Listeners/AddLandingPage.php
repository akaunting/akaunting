<?php

namespace Modules\PaypalStandard\Listeners;

use App\Events\Auth\LandingPageShowing as Event;

class AddLandingPage
{
    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $event->user->landing_pages['paypal-standard.settings.edit'] = trans('paypal-standard::general.name');
    }
}
