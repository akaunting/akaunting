<?php

namespace App\Http\Middleware;

use App\Utilities\Overrider;
use Closure;

class LoadSettings
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

        Overrider::load('settings');

        return $next($request);
    }

}