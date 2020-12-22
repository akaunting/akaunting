<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (auth()->guard($guard)->check()) {
            $user = user();

            if ($user->contact) {
                return redirect()->route('portal.dashboard');
            }

            return redirect()->route($user->landing_page);
        }

        return $next($request);
    }
}
