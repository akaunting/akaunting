<?php

namespace App\Utilities;

use Cache;
use Date;
use Module;

use App\Events\PaymentGatewayListing;

class Modules
{

    public static function getPaymentMethods($type = null)
    {

        $payment_methods = Cache::get('payment_methods.admin');

        $customer = auth()->user()->customer;

        if ($customer && $type != 'all') {
            $payment_methods = Cache::get('payment_methods.customer');
        }

        if (!empty($payment_methods)) {
            return $payment_methods;
        }

        $gateways = [];
        $methods = [];

        // Fire the event to extend the menu
        $results = event(new PaymentGatewayListing($gateways));

        foreach ($results as $gateways) {
            foreach ($gateways as $gateway) {
                if (!isset($gateway['name']) || !isset($gateway['code'])) {
                    continue;
                }

                if (($customer && empty($gateway['customer'])) && $type != 'all') {
                    continue;
                }

                $methods[] = $gateway;
            }
        }

        $sort_order = [];

        if ($methods) {
            foreach ($methods as $key => $value) {
                $sort_order[$key] = !empty($value['order']) ? $value['order'] : 0;
            }

            array_multisort($sort_order, SORT_ASC, $methods);

            foreach ($methods as $method) {
                $payment_methods[$method['code']] = $method['name'];
            }
        }

        if ($customer) {
            Cache::put('payment_methods.customer', $payment_methods, Date::now()->addHour(6));
        } else {
            Cache::put('payment_methods.admin', $payment_methods, Date::now()->addHour(6));
        }

        return ($payment_methods) ? $payment_methods : [];
    }
}
