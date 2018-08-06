<?php

namespace App\Http\Middleware;

use Closure;

class MoneyAmount
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

            $amount = money($request->get('amount'), $currency_code)->getAmount();

            $request->request->set('amount', $amount);
        }

        return $next($request);
    }
}
