<?php

namespace App\Http\Middleware;

use Closure;

class MoneyPrice
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
            $currency_code = $request->get('currency_code');

            if (empty($currency_code)) {
                $currency_code = setting('general.default_currency');
            }

            $price = money($request->get('price'), $currency_code)->getAmount();

            $request->request->set('price', $price);
        }

        return $next($request);
    }
}
