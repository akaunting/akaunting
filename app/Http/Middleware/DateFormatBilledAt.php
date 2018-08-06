<?php

namespace App\Http\Middleware;

use Closure;
use Date;

class DateFormatBilledAt
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
            $date = Date::parse($request->get('billed_at'))->format('Y-m-d');

            $date_time = $date . ' ' . Date::now()->format('H:i:s');

            $request->request->set('billed_at', $date_time);
        }

        return $next($request);
    }
}
