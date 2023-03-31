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
            if($request->getHost() == substr(env('SESSION_DOMAIN_MUKELLEF'), 1)){
                $domain = env('SESSION_DOMAIN_MUKELLEF');
            }
            else{
                $domain = env('SESSION_DOMAIN_WORKHY');
            }

            \Config::set('session.domain', $domain);
        }

        return $next($request);
    }

}
