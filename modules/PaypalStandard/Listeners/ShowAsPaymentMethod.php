<?php

namespace Modules\PaypalStandard\Listeners;

use App\Events\Module\PaymentMethodShowing as Event;

class ShowAsPaymentMethod
{
    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $method = setting('paypal-standard');

        $method['code'] = 'paypal-standard';

        $event->modules->payment_methods[] = $method;
    }
}
