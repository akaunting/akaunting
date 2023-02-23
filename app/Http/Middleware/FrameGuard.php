<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;

class FrameGuard
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
        $response = $next($request);
        $response->header('Content-Security-Policy', 'frame-ancestors '. Config::get('workhy.panel_url'));

        return $response;
    }
}
