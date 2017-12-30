<?php

namespace App\Http\Middleware;

use Akaunting\Money\Currency;
use App\Models\Setting\Currency as Model;
use Closure;

class LoadCurrencies
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
        $company_id = session('company_id');

        if (empty($company_id)) {
            return $next($request);
        }

        $currencies = Model::all();

        foreach ($currencies as $currency) {
            if (!isset($currency->precision)) {
                continue;
            }

            config(['money.' . $currency->code . '.precision' => $currency->precision]);
            config(['money.' . $currency->code . '.symbol' => $currency->symbol]);
            config(['money.' . $currency->code . '.symbol_first' => $currency->symbol_first]);
            config(['money.' . $currency->code . '.decimal_mark' => $currency->decimal_mark]);
            config(['money.' . $currency->code . '.thousands_separator' => $currency->thousands_separator]);
        }

        // Set currencies with new settings
        Currency::setCurrencies(config('money'));

        return $next($request);
    }

}