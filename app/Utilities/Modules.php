<?php

namespace App\Utilities;

use App\Events\Module\PaymentMethodShowing;
use Cache;
use Date;

class Modules
{
    public static function getPaymentMethods($type = null)
    {
        $cache_admin = static::getPaymentMethodsCacheKey('admin');
        $cache_customer = static::getPaymentMethodsCacheKey('customer');

        $payment_methods = Cache::get($cache_admin);

        $contact = true;

        if ($user = user()) {
            $contact = $user->contact;
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
        event(new PaymentMethodShowing($modules));

        foreach ((array) $modules->payment_methods as $method) {
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

    public static function clearPaymentMethodsCache()
    {
        Cache::forget(static::getPaymentMethodsCacheKey('admin'));
        Cache::forget(static::getPaymentMethodsCacheKey('customer'));
    }

    public static function getPaymentMethodsCacheKey($type)
    {
        return 'payment_methods.' . company_id() . '.' . $type;
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
