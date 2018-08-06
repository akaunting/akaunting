<?php

namespace App\Http\Middleware;

use Closure;
use Date;

class DateFormatPaidAt
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
            $date = Date::parse($request->get('paid_at'))->format('Y-m-d');

            $date_time = $date . ' ' . Date::now()->format('H:i:s');

            $request->request->set('paid_at', $date_time);
        }

        return $next($request);
    }
}
