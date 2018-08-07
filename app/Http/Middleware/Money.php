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
            $currency_code = $request->get('currency_code');

            if (empty($currency_code)) {
                $currency_code = setting('general.default_currency');
            }

            if (!empty($amount)) {
                $amount = money($request->get('amount'), $currency_code)->getAmount();

                $request->request->set('amount', $amount);
            }

            if (isset($bill_number) || isset($invoice_number)) {
                $items = $request->get('item');

                if (!empty($items)) {
                    foreach ($items as $key => $item) {
                        $items[$key]['price'] = money($item['price'], $currency_code)->getAmount();
                    }

                    $request->request->set('item', $items);
                }
            }
        }

        return $next($request);
    }
}
