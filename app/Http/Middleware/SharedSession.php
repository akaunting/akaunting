<?php

namespace App\Http\Middleware;

use Closure;
class SharedSession
{

    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(\App::isProduction()){
            if($request->getHost() == env('SESSION_DOMAIN_MUKELLEF')){
                \Config::set('session.domain', env('SESSION_DOMAIN_MUKELLEF'));
            }

            \Config::set('session.domain', env('SESSION_DOMAIN_WORKHY'));
        }

        return $next($request);
    }

}
