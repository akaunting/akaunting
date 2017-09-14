<?php

namespace App\Utilities;

use Cache;
use Date;
use Module;

use App\Events\PaymentGatewayListing;

class Modules
{

    public static function getPaymentMethods()
    {
        $payment_methods = Cache::get('payment_methods');

        if (!empty($payment_methods)) {
            return $payment_methods;
        }

        $gateways = array();

        // Fire the event to extend the menu
        $results = event(new PaymentGatewayListing($gateways));

        foreach ($results as $gateways) {
            foreach ($gateways as $gateway) {
                $methods[] = $gateway;
            }
        }

        $sort_order = array();

        foreach ($methods as $key => $value) {
            $sort_order[$key] = $value['order'];
        }

        array_multisort($sort_order, SORT_ASC, $methods);

        foreach ($methods as $method) {
            $payment_methods[$method['code']] = $method['name'];
        }

        Cache::put('payment_methods', $payment_methods, Date::now()->addHour(6));

        return $payment_methods;
    }
}
