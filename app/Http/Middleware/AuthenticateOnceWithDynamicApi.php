<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * AuthenticateOnceWithDynamicApi — Dispatcher
 *
 * Detects credential type from the Authorization header and delegates
 * to the appropriate dedicated middleware:
 *
 *   Authorization: Bearer <token>  →  AuthenticateOnceWithOAuth::handle()
 *   Authorization: Basic <base64>  →  AuthenticateOnceWithBasicAuth::handle()
 *   (no header)                    →  401
 *
 * Auth logic lives in the dedicated classes — extend/fix them independently.
 */
class AuthenticateOnceWithDynamicApi
{
    public function __construct(
        private readonly AuthenticateOnceWithOAuth $oauthMiddleware,
        private readonly AuthenticateOnceWithBasicAuth $basicMiddleware,
    ) {}

    public function handle(Request $request, Closure $next): mixed
    {
        // Skip if OAuth module is disabled to avoid unnecessary processing; delegate to Basic Auth directly since it's simpler and more likely to be used without OAuth.
        if (! config('oauth.enabled', false)) {
            return $this->basicMiddleware->handle($request, function (Request $req) use ($next) {
                $req->attributes->set('auth_method', 'basic');

                return $next($req);
            });
        }

        $authorization = (string) $request->header('Authorization', '');

        // ── Bearer token → delegate to OAuth middleware ───────────────────────
        if (preg_match('/^\s*Bearer\s+/i', $authorization) === 1) {
            return $this->oauthMiddleware->handle($request, function (Request $req) use ($next) {
                $req->attributes->set('auth_method', 'oauth');

                return $next($req);
            });
        }

        // ── Basic credentials → delegate to Basic Auth middleware ─────────────
        if (preg_match('/^\s*Basic\s+/i', $authorization) === 1) {
            return $this->basicMiddleware->handle($request, function (Request $req) use ($next) {
                $req->attributes->set('auth_method', 'basic');

                return $next($req);
            });
        }

        // ── No Authorization header → 401 ─────────────────────────────────────
        $realm = config('app.name', 'Akaunting');

        return response()->json([
            'message'           => 'Unauthenticated.',
            'error'             => 'invalid_client',
            'error_description' => 'Authentication required. Use an OAuth Bearer token or HTTP Basic credentials.',
        ], 401, [
            'WWW-Authenticate' => sprintf('Bearer realm="%s", Basic realm="%s"', $realm, $realm),
        ]);
    }
}
