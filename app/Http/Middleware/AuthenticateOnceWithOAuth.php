<?php

namespace App\Http\Middleware;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

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
        if ($this->authenticateWithSanctumToken($request)) {
            $request->attributes->set('auth_method', 'sanctum');

            return $next($request);
        }

        if (! config('oauth.enabled', false)) {
            return response()->json([
                'message' => 'Invalid credentials.',
                'error' => 'invalid_token',
                'error_description' => 'The access token provided is expired, revoked, malformed, or invalid.',
            ], 401);
        }

        $guard = config('oauth.guards.api', 'passport');
        $shouldLog = ! app()->environment('production');

        if ($shouldLog) {
            // Keep verbose diagnostics outside production only.
            Log::debug('OAuth: Attempting authentication', [
                'method' => $request->method(),
                'path' => $request->path(),
                'has_bearer' => $request->bearerToken() ? 'yes' : 'no',
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

    private function authenticateWithSanctumToken($request): bool
    {
        $plainTextToken = $request->bearerToken();

        if (empty($plainTextToken)) {
            return false;
        }

        $accessToken = PersonalAccessToken::findToken($plainTextToken);

        if (! $accessToken || ! $accessToken->tokenable) {
            return false;
        }

        if ($accessToken->expires_at && Carbon::parse($accessToken->expires_at)->isPast()) {
            return false;
        }

        $user = $accessToken->tokenable;

        $user->withAccessToken($accessToken);
        Auth::setUser($user);
        $accessToken->forceFill(['last_used_at' => now()])->save();

        return true;
    }
}
