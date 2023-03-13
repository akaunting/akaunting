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
        if (($request->method() == 'POST') || ($request->method() == 'PATCH')) {
            // todo fire event
            $fields = ['paid_at', 'due_at', 'issued_at', 'started_at', 'ended_at', 'expire_at', 'recurring_started_at', 'recurring_limit_date'];

            foreach ($fields as $field) {
                $date = $request->get($field);

                if (empty($date)) {
                    continue;
                }

                if (Date::parse($date)->format('H:i:s') == '00:00:00') {
                    $new_date = Date::parse($date)->format('Y-m-d')  . ' ' . Date::now()->format('H:i:s');
                } else {
                    $new_date = Date::parse($date)->toDateTimeString();
                }

                $request->request->set($field, $new_date);
            }
        }

        return $next($request);
    }
}
