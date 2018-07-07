<?php

namespace App\Http\Middleware;

use Closure;
use Date;
use Illuminate\Support\Facades\Request;

class BillDateFormat
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

        $uri = $request->path();
        $method = Request::method();

        if (($method == 'POST') || ($method == 'PATCH')) {
            $time = Date::now()->format('H:i:s');

            if (basename($uri) == 'payment') {
                $request['paid_at'] = $request['paid_at'] . ' ' . $time;
            } else {
                $request['billed_at'] = $request['billed_at'] . ' ' . $time;
                $request['due_at'] = $request['due_at'] . ' ' . $time;
            }
        }

        return $next($request);
    }
}
