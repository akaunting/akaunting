<?php

namespace Modules\OfflinePayments\Listeners;

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
        $methods = json_decode(setting('offline-payments.methods'), true);

        if (empty($methods)) {
            return;
        }

        foreach ($methods as $method) {
            $event->modules->payment_methods[] = $method;
        }
    }
}
