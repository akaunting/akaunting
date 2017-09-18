<?php

namespace Modules\OfflinePayment\Events\Handlers;

use App\Events\PaymentGatewayListing;

class OfflinePaymentGateway
{
    /**
     * Handle the event.
     *
     * @param  PaymentGatewayListing $event
     * @return void
     */
    public function handle(PaymentGatewayListing $event)
    {
        return json_decode(setting('offlinepayment.methods'), true);
    }
}
