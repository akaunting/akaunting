<?php

namespace Modules\OfflinePayments\Listeners;

use App\Events\Module\PaymentMethodShowing as Event;

class ShowPaymentMethod
{
    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $methods = json_decode(setting('offline-payments.methods'), true);

        foreach ($methods as $method) {
            $event->modules->payment_methods[] = $method;
        }
    }
}
