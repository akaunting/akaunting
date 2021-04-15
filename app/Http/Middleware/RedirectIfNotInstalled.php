<?php

namespace App\Http\Middleware;

use Closure;

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
        if (config('app.installed', false) == true) {
            return $next($request);
        }

        // Already in the installation wizard
        if ($request->isInstall()) {
            return $next($request);
        }

        // Not installed, redirect to installation wizard
        return redirect()->route('install.requirements');
    }
}
