<?php

namespace App\Http\Middleware;

use Closure;

class RedirectIfNoApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request"
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->get('alias') == 'core') {
            return $next($request);
        }

        if (setting('apps.api_key')) {
            return $next($request);
        }

        return redirect()->route('apps.api-key.create');
    }
}
