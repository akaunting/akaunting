<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;

class RedirectIfNotInstalled
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
        // Check application is installed or not
        if (env('APP_INSTALLED', false) == true) {
            return $next($request);
        }

        // Already in the installation wizard
        if (Str::startsWith($request->getPathInfo(), '/install')) {
            return $next($request);
        }

        // Not installed, redirect to installation wizard
        redirect()->route('install.requirements')->send();
    }
}
