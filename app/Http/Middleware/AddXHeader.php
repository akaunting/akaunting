<?php

namespace App\Http\Middleware;

use Closure;

class AddXHeader
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

        $response->header('X-Akaunting', 'Free Accounting Software');

        return $response;
    }
}