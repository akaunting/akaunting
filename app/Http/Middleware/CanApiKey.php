<?php

namespace App\Http\Middleware;

use Closure;

class CanApiKey
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
        if ($request['alias'] != 'core') {
            if (setting('apps.api_key')) {
                return $next($request);
            } else {
                redirect('apps/api-key/create')->send();
            }
        } else {
            return $next($request);
        }
    }
}