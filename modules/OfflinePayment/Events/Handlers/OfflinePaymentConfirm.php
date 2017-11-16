<?php

namespace Modules\OfflinePayment\Events\Handlers;

use App\Events\PaymentGatewayConfirm;

class OfflinePaymentConfirm
{
    /**
     * Handle the event.
     *
     * @param  PaymentGatewayConfirm $event
     * @return void
     */
    public function handle(PaymentGatewayConfirm $event)
    {
        /*if (strpos($event->gateway, 'offlinepayment') === false) {
            return false;
        }

        return [
            'code' => $event->gateway,
            'name' => $event->gateway,
            'redirect' => false,
            'html' => true,
        ];*/
    }
}
