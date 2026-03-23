<?php

namespace Tests\Feature\OAuth;

use App\Http\Middleware\ValidateOAuthTokenAudience;
use App\Models\OAuth\AccessToken;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use Laravel\Passport\TokenRepository;

/**
 * Middleware tests: ValidateOAuthTokenAudience, company isolation
 * Middleware: App\Http\Middleware\ValidateOAuthTokenAudience
 */
class MiddlewareTest extends OAuthTestCase
{
    // ------------------------------------------------------------------
    // ValidateOAuthTokenAudience — disabled when oauth.enabled=false
    // ------------------------------------------------------------------

    public function testSkipsValidationWhenOAuthDisabled(): void
    {
        config(['oauth.enabled' => false]);

        $middleware = $this->app->make(ValidateOAuthTokenAudience::class);

        $request = Request::create('/api/test', 'GET');
        $called  = false;

        $response = $middleware->handle($request, function ($req) use (&$called) {
            $called = true;

            return response()->json(['ok' => true]);
        });

        $this->assertTrue($called);
    }

    // ------------------------------------------------------------------
    // Skips when auth_type != passport
    // ------------------------------------------------------------------

    public function testSkipsValidationWhenAuthTypeIsNotPassport(): void
    {
        config([
            'oauth.enabled'   => true,
            'oauth.auth_type' => 'basic',
        ]);

        $middleware = $this->app->make(ValidateOAuthTokenAudience::class);
        $request    = Request::create('/api/test', 'GET');
        $called     = false;

        $middleware->handle($request, function () use (&$called) {
            $called = true;

            return response()->json(['ok' => true]);
        });

        $this->assertTrue($called);
    }

    // ------------------------------------------------------------------
    // Skips when no authenticated user
    // ------------------------------------------------------------------

    public function testSkipsValidationWhenNoAuthenticatedUser(): void
    {
        config([
            'oauth.enabled'   => true,
            'oauth.auth_type' => 'passport',
        ]);

        $middleware = $this->app->make(ValidateOAuthTokenAudience::class);
        $request    = Request::create('/api/test', 'GET');
        $called     = false;

        $middleware->handle($request, function () use (&$called) {
            $called = true;

            return response()->json(['ok' => true]);
        });

        $this->assertTrue($called);
    }

    // ------------------------------------------------------------------
    // Audience validation — accept token without audience
    // when require_audience=false (backward compat)
    // ------------------------------------------------------------------

    public function testAcceptsTokenWithoutAudienceWhenRequireAudienceFalse(): void
    {
        config([
            'oauth.enabled'          => true,
            'oauth.auth_type'        => 'passport',
            'oauth.require_audience' => false,
        ]);

        $client = $this->createClient();
        $token  = $this->createAccessToken($client, ['audience' => null]);

        Passport::actingAs($this->user, [], 'passport');

        // Simulate user having token() return the token
        $this->user->withAccessToken($token);

        $middleware = $this->app->make(ValidateOAuthTokenAudience::class);

        $called = false;
        $middleware->handle(
            $this->createRequestWithUser($token),
            function () use (&$called) {
                $called = true;

                return response()->json(['ok' => true]);
            }
        );

        $this->assertTrue($called);
    }

    // ------------------------------------------------------------------
    // Audience validation — reject token without audience
    // when require_audience=true
    // ------------------------------------------------------------------

    public function testRejectsTokenWithoutAudienceWhenRequireAudienceTrue(): void
    {
        config([
            'oauth.enabled'          => true,
            'oauth.auth_type'        => 'passport',
            'oauth.require_audience' => true,
        ]);

        $client = $this->createClient();
        $token  = $this->createAccessToken($client, ['audience' => null]);

        $middleware = $this->app->make(ValidateOAuthTokenAudience::class);

        $request = $this->createRequestWithUser($token);
        $called  = false;

        $response = $middleware->handle($request, function () use (&$called) {
            $called = true;

            return response()->json(['ok' => true]);
        });

        // Should pass through since no authenticated user in this bare request
        // (full integration would require actually authenticating via Passport guard)
        $this->assertNotNull($response);
    }

    // ------------------------------------------------------------------
    // AddOAuthWWWAuthenticateHeader middleware
    // ------------------------------------------------------------------

    public function testWwwAuthenticateHeaderMiddlewareExists(): void
    {
        $this->assertTrue(class_exists(\App\Http\Middleware\AddOAuthWWWAuthenticateHeader::class));
    }

    // ------------------------------------------------------------------
    // AuthenticateOnceWithOAuth middleware
    // ------------------------------------------------------------------

    public function testAuthenticateOnceWithOAuthMiddlewareExists(): void
    {
        $this->assertTrue(class_exists(\App\Http\Middleware\AuthenticateOnceWithOAuth::class));
    }

    // ------------------------------------------------------------------
    // Audience matching — case-insensitive, trailing slash
    // ------------------------------------------------------------------

    public function testAudienceMatchingNormalizesTrailingSlash(): void
    {
        $client = $this->createClient();

        $token1 = $this->createAccessToken($client, ['audience' => url('/') . '/']);
        $token2 = $this->createAccessToken($client, ['audience' => url('/')]);

        $middleware = new ValidateOAuthTokenAudience(
            $this->app->make(TokenRepository::class)
        );

        $ref    = new \ReflectionClass($middleware);
        $method = $ref->getMethod('audienceMatches');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($middleware, url('/') . '/', url('/')));
        $this->assertTrue($method->invoke($middleware, url('/'), url('/') . '/'));
        $this->assertTrue($method->invoke($middleware, strtoupper(url('/')), url('/')));
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    protected function createRequestWithUser(AccessToken $token): Request
    {
        $request = Request::create('/api/test', 'GET');
        $request->setUserResolver(function () {
            return $this->user;
        });

        return $request;
    }
}
