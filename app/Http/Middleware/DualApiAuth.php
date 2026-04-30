<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * DualApiAuth — Accepts EITHER a Bearer (Passport OAuth) token OR HTTP Basic
 * credentials on the same API route.
 *
 * Priority:
 *   1. Authorization: Bearer <token>  → Passport guard
 *      - Valid   → Auth::setUser(), continue
 *      - Invalid → 401  (never falls back to Basic when Bearer is present)
 *   2. Authorization: Basic <base64>  → onceBasic (email:password)
 *      - Valid   → continue
 *      - Invalid → 401
 *   3. Neither present → 401
 *
 * Backward-compat: existing Basic-Auth API clients are completely unaffected.
 * OAuth: company_id is resolved from the token by IdentifyCompany → getCompanyIdFromApi().
 */
class DualApiAuth
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
        // ── Path 1: Bearer token ──────────────────────────────────────────────
        if ($request->bearerToken()) {
            // Only try Passport when OAuth is enabled; otherwise treat the
            // Bearer header as a mis-configured Basic Auth attempt and let the
            // 401 below protect the endpoint.
            if (config('oauth.enabled', false)) {
                $guard = config('oauth.guards.api', 'passport');

                if (Auth::guard($guard)->check()) {
                    $user = Auth::guard($guard)->user();

                    // Pin the authenticated user on the default (web) guard so
                    // that auth()->user(), Laratrust permission checks, and
                    // IdentifyCompany all resolve without a session.
                    Auth::setUser($user);

                    // Mark request as OAuth-authenticated for downstream middleware
                    $request->attributes->set('auth_method', 'oauth');
                    $request->attributes->set('oauth_guard', $guard);

                    return $next($request);
                }
            }

            // Bearer present but invalid (or OAuth disabled) — reject immediately.
            // Never silently fall through to Basic Auth when a Bearer token was sent.
            return response()->json([
                'message'           => 'Unauthenticated.',
                'error'             => 'invalid_token',
                'error_description' => 'The access token provided is expired, revoked, malformed, or invalid for this server.',
            ], 401, [
                'WWW-Authenticate' => 'Bearer error="invalid_token", error_description="The access token is invalid"',
            ]);
        }

        // ── Path 2: HTTP Basic Auth ───────────────────────────────────────────
        $basicResult = Auth::onceBasic();

        if ($basicResult) {
            // onceBasic() returns a 401 Response on failure
            return $basicResult;
        }

        $request->attributes->set('auth_method', 'basic');

        return $next($request);
    }
}
