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
            $items = $request->get('items');

            if (!empty($amount)) {
                $amount = money($amount)->getAmount();

                $request->request->set('amount', $amount);
            }

            if (isset($bill_number) || isset($invoice_number) || !empty($items)) {
                if (!empty($items)) {
                    foreach ($items as $key => $item) {
                        if (!isset($item['price'])) {
                            continue;
                        }

                        $items[$key]['price'] = money($item['price'])->getAmount();
                    }

                    $request->request->set('items', $items);
                }
            }

            if (isset($opening_balance)) {
                $opening_balance = money($opening_balance)->getAmount();

                $request->request->set('opening_balance', $opening_balance);
            }
        }

        return $next($request);
    }
}
