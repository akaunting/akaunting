<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckForReadOnlyMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! config('read-only.enabled')) {
            return $next($request);
        }

        if (config('read-only.allow_login')) {
            $is_login = $request->routeIs(config('read-only.login_route'));
            $is_logout = $request->routeIs(config('read-only.logout_route'));

            if ($is_login || $is_logout) {
                return $next($request);
            }
        }

        foreach (config('read-only.whitelist') as $method => $route) {
            if (! $request->isMethod($method) || ! $request->routeIs($route)) {
                continue;
            }

            return $next($request);
        }

        foreach (config('read-only.livewire') as $path) {
            $url = company_id() . '/livewire/message/' . $path;

            if (! $request->isMethod('post') || ! $request->is($url)) {
                continue;
            }

            return $next($request);
        }

        foreach (config('read-only.methods') as $method) {
            if (! $request->isMethod(strtolower($method))) {
                continue;
            }

            //abort(Response::HTTP_UNAUTHORIZED);

            return response()->json([
                'success' => false,
                'error' => true,
                'data' => null,
                'message' => trans('maintenance.read_only'),
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
