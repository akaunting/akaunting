<?php

namespace App\Http\Middleware;

use Closure;
use OutOfBoundsException;
use Illuminate\Support\Str;
use InvalidArgumentException;
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

            if (isset($sale_price)) {
                $sale_price = Str::replaceFirst(',', '.', $sale_price);

                try {
                    $amount = money($sale_price)->getAmount();
                } catch (InvalidArgumentException | OutOfBoundsException | UnexpectedValueException $e) {
                    logger($e->getMessage());

                    $amount = 0;
                }

                $sale_price = $amount;

                $request->request->set('sale_price', $sale_price);
            }

            if (isset($purchase_price)) {
                $purchase_price = Str::replaceFirst(',', '.', $purchase_price);

                try {
                    $amount = money($purchase_price)->getAmount();
                } catch (InvalidArgumentException | OutOfBoundsException | UnexpectedValueException $e) {
                    logger($e->getMessage());

                    $amount = 0;
                }

                $purchase_price = $amount;

                $request->request->set('purchase_price', $purchase_price);
            }
        }

        return $next($request);
    }
}
