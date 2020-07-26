<?php

namespace Modules\OfflinePayments\Listeners;

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
        $event->user->landing_pages['offline-payments.settings.edit'] = trans('offline-payments::general.name');
    }
}
