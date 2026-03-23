<?php

namespace App\Providers;

use App\Models\OAuth\AccessToken;
use App\Models\OAuth\AuthCode;
use App\Models\OAuth\Client;
use App\Models\OAuth\PersonalAccessClient;
use App\Models\OAuth\RefreshToken;
use App\Repositories\OAuth\RefreshTokenRepository;
use App\Repositories\OAuth\TokenRepository;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Laravel\Passport\RefreshTokenRepository as PassportRefreshTokenRepository;
use Laravel\Passport\TokenRepository as PassportTokenRepository;

class OAuth extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Replace Passport's default RefreshTokenRepository with our company-scope-aware
        // version so that token lookups during the /oauth/token endpoint are never
        // inadvertently filtered by the current company context.
        $this->app->bind(
            PassportRefreshTokenRepository::class,
            RefreshTokenRepository::class
        );

        // Replace Passport's default TokenRepository for the same reason: the custom
        // AccessToken model carries a global "company" scope, so bearer-token validation
        // on any protected endpoint (e.g. /mcp) must bypass that scope when calling
        // isAccessTokenRevoked().
        $this->app->bind(
            PassportTokenRepository::class,
            TokenRepository::class
        );
    }

    /**
     * Bootstrap services.
     *
     * This method configures Laravel Passport to use Akaunting's
     * company-aware OAuth models instead of the default Passport models.
     *
     * @return void
     */
    public function boot()
    {
        // Only configure Passport if OAuth is enabled
        if (! config('oauth.enabled', false)) {
            return;
        }

        // Use custom models with company_id support
        Passport::useClientModel(Client::class);
        Passport::useTokenModel(AccessToken::class);
        Passport::useRefreshTokenModel(RefreshToken::class);
        Passport::useAuthCodeModel(AuthCode::class);
        Passport::usePersonalAccessClientModel(PersonalAccessClient::class);

        // Disable default Passport routes (we'll use custom routes)
        if (! config('passport.register_routes', false)) {
            Passport::ignoreRoutes();
        }

        // Warn if deprecated Password Grant is enabled
        if (config('oauth.password_grant_client.enabled', false)) {
            logger()->warning(
                'OAuth: Password Grant is enabled. This grant type is deprecated in OAuth 2.1. ' .
                'Consider migrating to Authorization Code Grant with PKCE. ' .
                'Set OAUTH_PASSWORD_GRANT_ENABLED=false to disable.'
            );
        }

        // Set token expiration from config
        Passport::tokensExpireIn(
            now()->addMinutes(config('oauth.expiration.access_token', 60))
        );

        Passport::refreshTokensExpireIn(
            now()->addMinutes(config('oauth.expiration.refresh_token', 20160))
        );

        Passport::personalAccessTokensExpireIn(
            now()->addMinutes(config('oauth.expiration.personal_access_token', 525600))
        );

        // Register OAuth scopes from database (with fallback to config)
        $scopes = $this->getOAuthScopes();
        if (!empty($scopes)) {
            Passport::tokensCan($scopes);
        }

        // Set default scope for OAuth tokens
        $defaultScope = $this->getDefaultScope();
        if ($defaultScope) {
            Passport::setDefaultScope($defaultScope);
        }

        // Enable client hashing if configured
        if (config('oauth.hash_client_secrets', false) || config('passport.hash_client_secrets', false)) {
            Passport::hashClientSecrets();
        }

        // Enable cookie serialization
        Passport::cookie(config('passport.cookie', 'laravel_token'));

        // Load migrations from package if needed
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(database_path('migrations'));
        }
    }

    /**
     * Get OAuth scopes from database with caching
     * Falls back to config if database is not available
     *
     * @return array
     */
    protected function getOAuthScopes(): array
    {
        try {
            // Try to get scopes from database with 1 hour cache
            return cache()->remember('oauth.scopes', 3600, function () {
                // Check if Scope model exists and table is ready
                if (!class_exists(\App\Models\OAuth\Scope::class)) {
                    return config('oauth.scopes', []);
                }

                try {
                    $scopes = \App\Models\OAuth\Scope::enabled()->ordered()->get();

                    if ($scopes->isEmpty()) {
                        return config('oauth.scopes', []);
                    }

                    return $scopes->mapWithKeys(function ($scope) {
                        return [$scope->key => $scope->description ?? $scope->name];
                    })->toArray();
                } catch (\Exception $e) {
                    // Table might not exist during migration
                    return config('oauth.scopes', []);
                }
            });
        } catch (\Exception $e) {
            // Fallback to config if any error occurs
            return config('oauth.scopes', []);
        }
    }

    /**
     * Get default OAuth scope from database
     * Falls back to config if not found
     *
     * @return string|null
     */
    protected function getDefaultScope(): ?string
    {
        try {
            return cache()->remember('oauth.default_scope', 3600, function () {
                if (!class_exists(\App\Models\OAuth\Scope::class)) {
                    return config('oauth.default_scope');
                }

                try {
                    $defaultScope = \App\Models\OAuth\Scope::default()->first();

                    return $defaultScope?->key ?? config('oauth.default_scope');
                } catch (\Exception $e) {
                    return config('oauth.default_scope');
                }
            });
        } catch (\Exception $e) {
            return config('oauth.default_scope');
        }
    }
}
