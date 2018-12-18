<?php

namespace Modules\PaypalStandard\Events\Handlers;

use App\Events\PaymentGatewayListing;

class PaypalStandardGateway
{
    /**
     * Handle the event.
     *
     * @param  PaymentGatewayListing $event
     * @return void
     */
    public function handle(PaymentGatewayListing $event)
    {
        $setting = setting('paypalstandard');

        $setting['code'] = 'paypalstandard';

        return [$setting];
    }
}
