<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthenticateOnceWithOAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        // Skip if OAuth module is disabled to avoid unnecessary processing
        if (! config('oauth.enabled', false)) {
            return $next($request);
        }

        $this->restoreAuthorizationHeaderIfMissing($request);

        $guard = config('oauth.guards.api', 'passport');
        $shouldLog = (bool) config('oauth.verbose_log', false) || ! app()->environment('production');

        if ($shouldLog) {
            // Keep verbose diagnostics outside production only.
            Log::debug('OAuth: Attempting authentication', [
                'method' => $request->method(),
                'path' => $request->path(),
                'has_bearer' => $request->bearerToken() ? 'yes' : 'no',
                'has_authorization_header' => $request->headers->has('Authorization') ? 'yes' : 'no',
                'guard' => $guard,
            ]);
        }

        // Check if user is authenticated via Passport
        if (! Auth::guard($guard)->check()) {
            if ($shouldLog) {
                Log::warning('OAuth: Authentication failed', [
                    'guard' => $guard,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            }

            return response()->json([
                'message' => 'Unauthenticated.',
                'error' => 'invalid_token',
                'error_description' => 'The access token provided is expired, revoked, malformed, or invalid.',
            ], 401);
        }

        // Fire authenticated event with passport protocol
        if ($user = Auth::guard($guard)->user()) {
            if ($shouldLog) {
                Log::debug('OAuth: Authentication successful', [
                    'user_id' => $user->id,
                ]);
            }

            // Set the user on the default guard so that the user() helper
            // and middleware like IdentifyCompany (which calls auth()->user())
            // can resolve the authenticated user without starting a session.
            Auth::setUser($user);
        }

        return $next($request);
    }

    /**
     * Restore Authorization header from common proxy server variables.
     *
     * Some reverse proxies do not pass the Authorization header directly,
     * but expose it via alternate server variables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function restoreAuthorizationHeaderIfMissing($request): void
    {
        if (! empty($request->header('Authorization'))) {
            return;
        }

        $candidates = [
            $request->server('HTTP_AUTHORIZATION'),
            $request->server('REDIRECT_HTTP_AUTHORIZATION'),
            $request->server('HTTP_X_FORWARDED_AUTHORIZATION'),
            $request->header('X-Forwarded-Authorization'),
            $request->header('X-Original-Authorization'),
        ];

        foreach ($candidates as $candidate) {
            if (! is_string($candidate) || trim($candidate) === '') {
                continue;
            }

            $request->headers->set('Authorization', $candidate);

            return;
        }
    }
}
