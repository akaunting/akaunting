<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Handle an incoming request.
     *
     * When the request carries a Bearer token but authentication fails, return
     * an RFC 6750 compliant error payload instead of the generic "Unauthenticated".
     */
    public function handle($request, Closure $next, ...$guards)
    {
        try {
            return parent::handle($request, $next, ...$guards);
        } catch (AuthenticationException $e) {
            if ($request->bearerToken()) {
                return response()->json([
                    'error'             => 'invalid_token',
                    'error_description' => 'The access token provided is expired, revoked, malformed, or invalid.',
                ], 401, [
                    'WWW-Authenticate' => 'Bearer error="invalid_token", error_description="Invalid or expired token"',
                ]);
            }

            throw $e;
        }
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }
}
