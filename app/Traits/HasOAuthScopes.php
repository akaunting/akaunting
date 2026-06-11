<?php

namespace App\Traits;

use Modules\Oauth\Services\ScopeMapper;
use Illuminate\Support\Collection;

/**
 * HasOAuthScopes – User-level OAuth scope helpers.
 *
 * Add this trait to App\Models\Auth\User (alongside HasApiTokens and
 * LaratrustUserTrait) to get scope-awareness on the user model.
 *
 * All scope → permission logic lives in ScopeMapper; this trait is just
 * a thin, cache-backed convenience layer for the User model.
 */
trait HasOAuthScopes
{
    /**
     * In-memory cache so that availableOAuthScopes() isn't recomputed on
     * every call within the same request lifecycle.
     */
    protected ?Collection $_cachedOAuthScopes = null;

    // -------------------------------------------------------------------------
    // Scope discovery
    // -------------------------------------------------------------------------

    /**
     * Return all OAuth scope keys this user is eligible to grant.
     *
     * Derived dynamically from the user's Laratrust permissions; no hardcoding.
     * Result is cached per-instance for the lifetime of the request.
     *
     * Example output: ['banking:read', 'invoices:read', 'sales:write', ...]
     */
    public function availableOAuthScopes(): Collection
    {
        if ($this->_cachedOAuthScopes === null) {
            $this->_cachedOAuthScopes = ScopeMapper::scopesForUser($this);
        }

        return $this->_cachedOAuthScopes;
    }

    /**
     * Flush the cached scope list.
     * Call this after a role/permission change to get fresh results.
     */
    public function flushOAuthScopeCache(): void
    {
        $this->_cachedOAuthScopes = null;
    }

    // -------------------------------------------------------------------------
    // Scope eligibility checks
    // -------------------------------------------------------------------------

    /**
     * Check whether the user can grant a specific scope.
     * (i.e., the user holds at least one permission covered by that scope.)
     *
     * Example:
     *   $user->canGrantScope('sales:read')   // true if user has read-sales-*
     *   $user->canGrantScope('banking:write') // true if user has create/update-banking-*
     */
    public function canGrantScope(string $scope): bool
    {
        return $this->availableOAuthScopes()->contains($scope);
    }

    /**
     * Filter a requested scope list down to only the scopes this user
     * is actually eligible to grant.
     *
     * Useful in the authorization controller to sanitize client-requested scopes.
     *
     * Example:
     *   $requested = ['sales:read', 'banking:delete', 'mcp:use']
     *   $user->filterGrantableScopes($requested)
     *   // → ['sales:read', 'mcp:use']  (assuming no banking:delete permission)
     */
    public function filterGrantableScopes(array $scopes): array
    {
        // mcp:use and other manual scopes bypass the permission check
        $manualScopes = ScopeMapper::MANUAL_SCOPES;

        return array_values(array_filter($scopes, function (string $scope) use ($manualScopes) {
            if (in_array($scope, $manualScopes, true)) {
                return true;
            }

            return $this->canGrantScope($scope);
        }));
    }

    // -------------------------------------------------------------------------
    // Token scope satisfaction
    // -------------------------------------------------------------------------

    /**
     * Check whether any scope on the current request token satisfies
     * the given Laratrust permission name.
     *
     * Call this inside controllers/policies when you want to enforce that
     * the OAuth client was actually granted access to a resource.
     *
     * Example:
     *   $user->currentTokenSatisfies('read-sales-invoices')
     *   // → true when token has 'sales:read' or 'read-sales-invoices' directly
     */
    public function currentTokenSatisfies(string $permission): bool
    {
        $token = $this->token();

        if (!$token) {
            return false;
        }

        return $this->tokenSatisfies($token, $permission);
    }

    /**
     * Check whether a specific token's scopes satisfy a given permission.
     *
     * Accepts both the scope-format ('sales:read') and the permission name
     * directly ('read-sales-invoices') in the token's scope list.
     *
     * Example:
     *   $user->tokenSatisfies($token, 'read-sales-invoices')
     */
    public function tokenSatisfies($token, string $permission): bool
    {
        $scopes = $token->scopes ?? [];

        // 1. Direct match: the token scope IS the permission name itself
        if (in_array($permission, $scopes, true)) {
            return true;
        }

        // 2. Derived match: e.g. 'sales:read' covers 'read-sales-invoices'
        return ScopeMapper::anyScopeSatisfies($scopes, $permission);
    }
}
