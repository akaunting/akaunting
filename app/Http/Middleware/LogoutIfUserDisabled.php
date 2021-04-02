<?php

namespace App\Http\Middleware;

use Closure;

class LogoutIfUserDisabled
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
        $user = user();

        if (!$user || $user->enabled) {
            return $next($request);
        }

        auth()->logout();

        return redirect()->route('login');
    }
}
