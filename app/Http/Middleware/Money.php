<?php

namespace App\Http\Middleware;

use Closure;

class Money
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->method() == 'POST' || $request->method() == 'PATCH') {
            $amount = $request->get('amount');
            $bill_number = $request->get('bill_number');
            $invoice_number = $request->get('invoice_number');
            $sale_price = $request->get('sale_price');
            $purchase_price = $request->get('purchase_price');
            $opening_balance = $request->get('opening_balance');
            $currency_code = $request->get('currency_code');
            $items = $request->get('item');

            if (empty($currency_code)) {
                $currency_code = setting('general.default_currency');
            }

            if (!empty($amount)) {
                $amount = money($request->get('amount'), $currency_code)->getAmount();

                $request->request->set('amount', $amount);
            }

            if (isset($bill_number) || isset($invoice_number) || !empty($items)) {
                if (!empty($items)) {
                    foreach ($items as $key => $item) {
                        if (!isset($item['price'])) {
                            continue;
                        }

                        if (isset($item['currency']) && $item['currency'] != $currency_code) {
                            $items[$key]['price'] = money($item['price'], $item['currency'])->getAmount();
                        } else {
                            $items[$key]['price'] = money($item['price'], $currency_code)->getAmount();
                        }
                    }

                    $request->request->set('item', $items);
                }
            }

            if (isset($opening_balance)) {
                $opening_balance = money($opening_balance, $currency_code)->getAmount();

                $request->request->set('opening_balance', $opening_balance);
            }

            /* check item price use money
            if (isset($sale_price)) {
                $sale_price = money($sale_price, $currency_code)->getAmount();

                $request->request->set('sale_price', $sale_price);
            }

            if (isset($purchase_price)) {
                $purchase_price = money($purchase_price, $currency_code)->getAmount();

                $request->request->set('purchase_price', $purchase_price);
            }
            */
        }

        return $next($request);
    }
}
