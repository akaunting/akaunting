<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfInstalled
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
        // DB_DATABASE not empty means installed
        if (env('DB_DATABASE', '') != '') {
            return $next($request);
        }

        // Already in the wizard
        if (starts_with($request->getPathInfo(), '/install')) {
            return $next($request);
        }

        // Not installed, redirect to installation wizard
        redirect('install/requirements')->send();
    }
}
