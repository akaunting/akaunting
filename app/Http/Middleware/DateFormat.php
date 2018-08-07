<?php

namespace App\Http\Middleware;

use Closure;
use Date;

class DateFormat
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
            $paid_at = $request->get('paid_at');
            $due_at = $request->get('due_at');
            $billed_at = $request->get('billed_at');
            $invoiced_at = $request->get('invoiced_at');

            if (!empty($paid_at)) {
                $paid_at = Date::parse($paid_at)->format('Y-m-d');

                $date_time = $paid_at . ' ' . Date::now()->format('H:i:s');

                $request->request->set('paid_at', $date_time);
            }

            if (!empty($due_at)) {
                $due_at = Date::parse($due_at)->format('Y-m-d');

                $date_time = $due_at . ' ' . Date::now()->format('H:i:s');

                $request->request->set('due_at', $date_time);
            }

            if (!empty($billed_at)) {
                $billed_at = Date::parse($billed_at)->format('Y-m-d');

                $date_time = $billed_at . ' ' . Date::now()->format('H:i:s');

                $request->request->set('billed_at', $date_time);
            }

            if (!empty($invoiced_at)) {
                $invoiced_at = Date::parse($invoiced_at)->format('Y-m-d');

                $date_time = $invoiced_at . ' ' . Date::now()->format('H:i:s');

                $request->request->set('invoiced_at', $date_time);
            }
        }

        return $next($request);
    }
}
