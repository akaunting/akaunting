<?php

namespace App\Utilities;

use Cache;
use Date;

class Modules
{
    public static function getPaymentMethods($type = null)
    {
        $company_id = session('company_id');

        $cache_admin = 'payment_methods.' . $company_id . '.admin';
        $cache_customer = 'payment_methods.' . $company_id . '.customer';

        $payment_methods = Cache::get($cache_admin);

        $contact = true;

        if (user()) {
            $contact = user()->contact;
        }

        if ($contact && ($type != 'all')) {
            $payment_methods = Cache::get($cache_customer);
        }

        if (!empty($payment_methods)) {
            return $payment_methods;
        }

        $list = [];

        $modules = new \stdClass();
        $modules->payment_methods = [];

        // Fire the event to get the list of payment methods
        event(new \App\Events\Module\PaymentMethodShowing($modules));

        foreach ($modules->payment_methods as $method) {
            if (!isset($method['name']) || !isset($method['code'])) {
                continue;
            }

            if (($contact && empty($method['customer'])) && ($type != 'all')) {
                continue;
            }

            $list[] = $method;
        }

        static::sortPaymentMethods($list);

        foreach ($list as $method) {
            $payment_methods[$method['code']] = $method['name'];
        }

        if ($contact) {
            Cache::put($cache_customer, $payment_methods, Date::now()->addHour(6));
        } else {
            Cache::put($cache_admin, $payment_methods, Date::now()->addHour(6));
        }

        return ($payment_methods) ? $payment_methods : [];
    }

    protected static function sortPaymentMethods(&$list)
    {
        $sort_order = [];

        foreach ($list as $key => $value) {
            $sort_order[$key] = !empty($value['order']) ? $value['order'] : 0;
        }

        if (empty($sort_order)) {
            return;
        }

        array_multisort($sort_order, SORT_ASC, $list);
    }
}
