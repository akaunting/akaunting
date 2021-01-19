<?php

namespace App\Http\Middleware;

use Closure;
use InvalidArgumentException;
use OutOfBoundsException;
use UnexpectedValueException;
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
            $document_number = $request->get('document_number');
            $sale_price = $request->get('sale_price');
            $purchase_price = $request->get('purchase_price');
            $opening_balance = $request->get('opening_balance');
            $items = $request->get('items');

            if (!empty($amount)) {
                try {
                    $amount = money($amount)->getAmount();
                } catch (InvalidArgumentException | OutOfBoundsException | UnexpectedValueException $e) {
                    logger($e->getMessage());

                    $amount = 0;
                }

                $request->request->set('amount', $amount);
            }

            if (isset($document_number) || !empty($items)) {
                if (!empty($items)) {
                    foreach ($items as $key => $item) {
                        if (!isset($item['price'])) {
                            continue;
                        }

                        try {
                            $amount = money($item['price'])->getAmount();
                        } catch (InvalidArgumentException | OutOfBoundsException | UnexpectedValueException $e) {
                            logger($e->getMessage());

                            $amount = 0;
                        }

                        $items[$key]['price'] = $amount;
                    }

                    $request->request->set('items', $items);
                }
            }

            if (isset($opening_balance)) {
                try {
                    $amount = money($opening_balance)->getAmount();
                } catch (InvalidArgumentException | OutOfBoundsException | UnexpectedValueException $e) {
                    logger($e->getMessage());

                    $amount = 0;
                }

                $opening_balance = $amount;

                $request->request->set('opening_balance', $opening_balance);
            }
        }

        return $next($request);
    }
}
