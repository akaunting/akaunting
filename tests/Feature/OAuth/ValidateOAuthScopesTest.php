<?php

namespace Tests\Feature\OAuth;

use App\Http\Middleware\ValidateOAuthScopes;
use App\Models\OAuth\AccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;

/**
 * ValidateOAuthScopes middleware
 *
 * Covers:
 *   - Basic Auth requests pass through (middleware is OAuth-only)
 *   - mcp:use super-scope bypasses all permission checks
 *   - Matching scope → access granted
 *   - Non-matching scope → 403 insufficient_scope
 *   - No required permission specified + has scopes → pass
 *   - No required permission specified + empty scopes → deny
 *   - oauth.validate_scopes=false globally disables check
 *   - 403 response shape and WWW-Authenticate header
 *   - Multiple scopes: any one matching is enough
 */
class ValidateOAuthScopesTest extends OAuthTestCase
{
    private ValidateOAuthScopes $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = $this->app->make(ValidateOAuthScopes::class);
        config(['oauth.validate_scopes' => true]);
    }

    // ------------------------------------------------------------------
    // Basic Auth bypass
    // ------------------------------------------------------------------

    public function testBasicAuthRequestPassesThroughWithoutScopeCheck(): void
    {
        Auth::login($this->user);

        $request = $this->makeOAuthRequest([]);    // no scopes, not OAuth
        $request->attributes->set('auth_method', 'basic');

        $called = false;
        $this->middleware->handle($request, function () use (&$called) {
            $called = true;

            return response()->json(['ok' => true]);
        }, 'read-accounts');

        $this->assertTrue($called, 'Basic auth should bypass scope validation');
    }

    // ------------------------------------------------------------------
    // mcp:use super-scope
    // ------------------------------------------------------------------

    public function testMcpUseScopeGrantsAccessToAnyEndpoint(): void
    {
        $client = $this->createClient();
        $token  = $this->createAccessToken($client, ['scopes' => ['mcp:use']]);

        $request = $this->makeOAuthRequest(['mcp:use'], $token);

        $called = false;
        $this->middleware->handle($request, function () use (&$called) {
            $called = true;

            return response()->json(['ok' => true]);
        }, 'read-accounts');

        $this->assertTrue($called, 'mcp:use should bypass permission requirement');
    }

    public function testMcpUseScopeGrantsAccessEvenWithStrictPermission(): void
    {
        $client = $this->createClient();
        $token  = $this->createAccessToken($client, ['scopes' => ['mcp:use']]);
        $request = $this->makeOAuthRequest(['mcp:use'], $token);

        $called = false;
        $this->middleware->handle($request, function () use (&$called) {
            $called = true;

            return response()->json(['ok' => true]);
        }, 'delete-documents');   // very specific permission

        $this->assertTrue($called);
    }

    // ------------------------------------------------------------------
    // Matching scope grants access
    // ------------------------------------------------------------------

    public function testBankingReadScopeSatisfiesReadAccountsPermission(): void
    {
        $client  = $this->createClient();
        $token   = $this->createAccessToken($client, ['scopes' => ['banking:read']]);
        $request = $this->makeOAuthRequest(['banking:read'], $token);

        $called = false;
        $this->middleware->handle($request, function () use (&$called) {
            $called = true;

            return response()->json(['ok' => true]);
        }, 'read-accounts');

        $this->assertTrue($called);
    }

    public function testSalesWriteScopeSatisfiesCreateDocumentsPermission(): void
    {
        $client  = $this->createClient();
        $token   = $this->createAccessToken($client, ['scopes' => ['sales:write']]);
        $request = $this->makeOAuthRequest(['sales:write'], $token);

        $called = false;
        $this->middleware->handle($request, function () use (&$called) {
            $called = true;

            return response()->json(['ok' => true]);
        }, 'create-documents');

        $this->assertTrue($called);
    }

    public function testAnyMatchingScopeInListGrantsAccess(): void
    {
        $client  = $this->createClient();
        $scopes  = ['sales:read', 'banking:read'];
        $token   = $this->createAccessToken($client, ['scopes' => $scopes]);
        $request = $this->makeOAuthRequest($scopes, $token);

        $called = false;
        $this->middleware->handle($request, function () use (&$called) {
            $called = true;

            return response()->json(['ok' => true]);
        }, 'read-accounts');

        $this->assertTrue($called);
    }

    // ------------------------------------------------------------------
    // Non-matching scope → 403
    // ------------------------------------------------------------------

    public function testSalesReadDoesNotSatisfyReadAccountsPermission(): void
    {
        $client  = $this->createClient();
        $token   = $this->createAccessToken($client, ['scopes' => ['sales:read']]);
        $request = $this->makeOAuthRequest(['sales:read'], $token);

        $response = $this->middleware->handle($request, fn () => response()->json(['ok' => true]), 'read-accounts');

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testDeniedResponseHasCorrectErrorShape(): void
    {
        $client  = $this->createClient();
        $token   = $this->createAccessToken($client, ['scopes' => ['sales:read']]);
        $request = $this->makeOAuthRequest(['sales:read'], $token);

        $response = $this->middleware->handle($request, fn () => response()->json([]), 'read-accounts');
        $body     = json_decode($response->getContent(), true);

        $this->assertEquals('insufficient_scope', $body['error']);
        $this->assertArrayHasKey('error_description', $body);
        $this->assertStringContainsString('read-accounts', $body['error_description']);
    }

    public function testDeniedResponseHasWwwAuthenticateHeader(): void
    {
        $client  = $this->createClient();
        $token   = $this->createAccessToken($client, ['scopes' => ['sales:read']]);
        $request = $this->makeOAuthRequest(['sales:read'], $token);

        $response = $this->middleware->handle($request, fn () => response()->json([]), 'read-accounts');

        $this->assertTrue($response->headers->has('WWW-Authenticate'));
        $this->assertStringContainsString('insufficient_scope', $response->headers->get('WWW-Authenticate'));
    }

    // ------------------------------------------------------------------
    // No required permission specified
    // ------------------------------------------------------------------

    public function testPassesThroughWhenNoPermissionRequiredAndTokenHasScopes(): void
    {
        $client  = $this->createClient();
        $token   = $this->createAccessToken($client, ['scopes' => ['banking:read']]);
        $request = $this->makeOAuthRequest(['banking:read'], $token);

        $called = false;
        $this->middleware->handle($request, function () use (&$called) {
            $called = true;

            return response()->json(['ok' => true]);
        });  // no required permission arg

        $this->assertTrue($called);
    }

    public function testDeniesWhenNoPermissionRequiredButTokenHasNoScopes(): void
    {
        $client  = $this->createClient();
        $token   = $this->createAccessToken($client, ['scopes' => []]);
        $request = $this->makeOAuthRequest([], $token);

        $response = $this->middleware->handle($request, fn () => response()->json(['ok' => true]));

        $this->assertEquals(403, $response->getStatusCode());
    }

    // ------------------------------------------------------------------
    // Global disable switch
    // ------------------------------------------------------------------

    public function testSkipsAllValidationWhenValidateScopesDisabled(): void
    {
        config(['oauth.validate_scopes' => false]);

        $client  = $this->createClient();
        $token   = $this->createAccessToken($client, ['scopes' => []]); // empty scopes
        $request = $this->makeOAuthRequest([], $token);

        $called = false;
        $this->middleware->handle($request, function () use (&$called) {
            $called = true;

            return response()->json(['ok' => true]);
        }, 'delete-documents');  // would normally deny

        $this->assertTrue($called, 'Scope validation should be skipped when disabled');
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    /**
     * Build a request marked as OAuth-authenticated with the given scopes.
     */
    private function makeOAuthRequest(array $scopes, ?AccessToken $token = null): Request
    {
        $request = Request::create('/' . company_id() . '/api/accounts', 'GET');
        $request->attributes->set('auth_method', 'oauth');

        if ($token) {
            $request->setUserResolver(function () use ($token) {
                $this->user->withAccessToken($token);

                return $this->user;
            });
        }

        return $request;
    }
}
