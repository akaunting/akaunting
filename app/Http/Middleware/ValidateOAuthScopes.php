<?php

namespace App\Http\Middleware;

use App\Services\OAuth\ScopeMapper;
use Closure;
use Illuminate\Http\Request;

/**
 * ValidateOAuthScopes — Scope-based permission guard for OAuth API requests.
 *
 * When a request is authenticated via an OAuth Bearer token, this middleware
 * additionally verifies that the token's scopes cover the Laratrust permission
 * required for the current route action.
 *
 * Usage (in controller constructor):
 *   $this->middleware('oauth.scopes:read-accounts')->only('index', 'show');
 *   $this->middleware('oauth.scopes:create-accounts')->only('store');
 *
 * If no required permission is passed as a parameter, the middleware checks
 * that the token has at least ONE valid scope (i.e. is not completely empty).
 *
 * Basic-Auth requests are skipped entirely — scope validation is only for OAuth.
 *
 * Mapping examples (via ScopeMapper):
 *   token scope 'banking:read'  satisfies  'read-accounts'    ✓
 *   token scope 'banking:read'  satisfies  'create-accounts'  ✗
 *   token scope 'sales:write'   satisfies  'create-documents' ✓
 *   token scope 'mcp:use'       satisfies  anything           ✓ (super-scope)
 */
class ValidateOAuthScopes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $requiredPermission  Laratrust permission to validate against.
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ?string $requiredPermission = null)
    {
        // Only applies to OAuth-authenticated requests
        if ($request->attributes->get('auth_method') !== 'oauth') {
            return $next($request);
        }

        // Skip if OAuth scope validation is disabled globally
        if (! config('oauth.validate_scopes', true)) {
            return $next($request);
        }

        $user  = auth()->user();
        $token = $user?->token();

        if (! $token) {
            return $this->forbidden('No valid token found for this request.');
        }

        $tokenScopes = $token->scopes ?? [];

        // mcp:use is a super-scope — grants access to all API endpoints
        if (in_array('mcp:use', $tokenScopes, true)) {
            return $next($request);
        }

        // No required permission specified — just confirm the token has scopes
        if ($requiredPermission === null) {
            if (empty($tokenScopes)) {
                return $this->forbidden('The token has no scopes and cannot access this endpoint.');
            }

            return $next($request);
        }

        // Check whether any of the token's scopes satisfy the required permission
        if (ScopeMapper::anyScopeSatisfies($tokenScopes, $requiredPermission)) {
            return $next($request);
        }

        return $this->forbidden(
            "The token's scopes do not grant access to '{$requiredPermission}'. " .
            "Token scopes: [" . implode(', ', $tokenScopes) . ']'
        );
    }

    /**
     * Return a 403 Forbidden JSON response.
     */
    protected function forbidden(string $message): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'message'           => 'Forbidden.',
            'error'             => 'insufficient_scope',
            'error_description' => $message,
        ], 403, [
            'WWW-Authenticate' => 'Bearer error="insufficient_scope"',
        ]);
    }
}
