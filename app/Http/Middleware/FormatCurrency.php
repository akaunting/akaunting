<?php

namespace App\Http\Middleware;

use App\Models\Setting\Currency;
use Closure;

class FormatCurrency
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
        $data = $request->toArray();
        $price = $data['item'][0]['price'];
        try {
            $price = preg_replace("/(\d+)\,(\d\d$)/", "$1.$2", $price);
            $data['item'][0]['price'] = $price;
            $request->replace($data);
        }
        catch (Exception $e) {
            pass;
        }
        return $next($request);
    }
}
