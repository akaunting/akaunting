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
        if (strpos($event->gateway, 'offlinepayment') === false) {
            return [];
        }

        $invoice = $event->invoice;

        $gateway = [];

        $payment_methods = json_decode(setting('offlinepayment.methods'), true);

        foreach ($payment_methods as $payment_method) {
            if ($payment_method['code'] == $event->gateway) {
                $gateway = $payment_method;

                break;
            }
        }

        $html = view('offlinepayment::confirm', compact('gateway', 'invoice'))->render();

        return [
            'code' => $gateway['code'],
            'name' => $gateway['name'],
            'description' => $gateway['description'],
            'redirect' => false,
            'html' => $html,
        ];
    }
}
