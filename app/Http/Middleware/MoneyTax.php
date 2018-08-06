<?php

namespace App\Http\Middleware;

use Closure;

class MoneyTax
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

            $tax = money($request->get('tax'), $currency_code)->getAmount();

            $request->request->set('tax', $tax);
        }

        return $next($request);
    }
}
