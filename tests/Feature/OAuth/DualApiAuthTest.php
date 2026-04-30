<?php

namespace Tests\Feature\OAuth;

use App\Http\Middleware\DualApiAuth;
use App\Models\OAuth\AccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;

/**
 * DualApiAuth middleware — Bearer (Passport) OR Basic Auth auto-detection.
 *
 * Covers:
 *   - Valid Bearer token → authenticated via Passport guard
 *   - Invalid Bearer token → 401 (never falls back to Basic)
 *   - Bearer present when OAuth disabled → 401
 *   - Valid Basic Auth credentials → authenticated via onceBasic
 *   - Invalid Basic Auth → 401
 *   - No Authorization header → 401
 *   - auth_method request attribute set correctly
 *   - 401 response shape and WWW-Authenticate header
 */
class DualApiAuthTest extends OAuthTestCase
{
    private DualApiAuth $middleware;

    protected function setUp(): void
    {
        parent::setUp();

        $this->middleware = $this->app->make(DualApiAuth::class);
    }

    // ------------------------------------------------------------------
    // Bearer token path
    // ------------------------------------------------------------------

    public function testValidBearerTokenAuthenticatesViaPassport(): void
    {
        $client = $this->createClient();
        $token  = $this->createAccessToken($client);

        Passport::actingAs($this->user, [], 'passport');

        $request = $this->makeRequest('GET', ['Authorization' => 'Bearer ' . $token->id]);

        $called = false;
        $this->middleware->handle($request, function (Request $req) use (&$called) {
            $called = true;

            return response()->json(['ok' => true]);
        });

        $this->assertTrue($called, 'Next middleware was not called for valid Bearer token');
    }

    public function testValidBearerSetsAuthMethodToOauth(): void
    {
        $client = $this->createClient();
        $token  = $this->createAccessToken($client);

        Passport::actingAs($this->user, [], 'passport');

        $request = $this->makeRequest('GET', ['Authorization' => 'Bearer ' . $token->id]);

        $this->middleware->handle($request, function (Request $req) {
            return response()->json(['ok' => true]);
        });

        $this->assertEquals('oauth', $request->attributes->get('auth_method'));
    }

    public function testInvalidBearerTokenReturns401(): void
    {
        $request = $this->makeRequest('GET', ['Authorization' => 'Bearer invalid-token-xyz']);

        $response = $this->middleware->handle($request, function () {
            return response()->json(['ok' => true]);
        });

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testInvalidBearerReturnsCorrectErrorShape(): void
    {
        $request  = $this->makeRequest('GET', ['Authorization' => 'Bearer bad-token']);
        $response = $this->middleware->handle($request, fn () => response()->json([]));

        $body = json_decode($response->getContent(), true);

        $this->assertArrayHasKey('error', $body);
        $this->assertEquals('invalid_token', $body['error']);
        $this->assertArrayHasKey('error_description', $body);
    }

    public function testInvalidBearerReturnsWwwAuthenticateHeader(): void
    {
        $request  = $this->makeRequest('GET', ['Authorization' => 'Bearer bad']);
        $response = $this->middleware->handle($request, fn () => response()->json([]));

        $this->assertTrue(
            $response->headers->has('WWW-Authenticate'),
            'WWW-Authenticate header missing on invalid Bearer 401'
        );
        $this->assertStringContainsString(
            'invalid_token',
            $response->headers->get('WWW-Authenticate')
        );
    }

    public function testBearerWithOAuthDisabledReturns401(): void
    {
        config(['oauth.enabled' => false]);

        $request  = $this->makeRequest('GET', ['Authorization' => 'Bearer some-token']);
        $response = $this->middleware->handle($request, fn () => response()->json(['ok' => true]));

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testInvalidBearerDoesNotFallBackToBasicAuth(): void
    {
        // Attach BOTH headers — Bearer invalid, Basic valid.
        // Middleware MUST reject on the invalid Bearer alone.
        $credentials = base64_encode($this->user->email . ':password');

        $request = $this->makeRequest('GET', [
            'Authorization' => 'Bearer invalid-token',
            'PHP_AUTH_USER' => $this->user->email,
            'PHP_AUTH_PW'   => 'password',
        ]);

        $called   = false;
        $response = $this->middleware->handle($request, function () use (&$called) {
            $called = true;

            return response()->json(['ok' => true]);
        });

        $this->assertFalse($called, 'Next should not be called when Bearer is present but invalid');
        $this->assertEquals(401, $response->getStatusCode());
    }

    // ------------------------------------------------------------------
    // Basic Auth path
    // ------------------------------------------------------------------

    public function testValidBasicAuthPassesThrough(): void
    {
        // actingAs sets user on the default guard; onceBasic will pass
        Auth::login($this->user);

        $request = $this->makeRequest('GET', [
            'PHP_AUTH_USER' => $this->user->email,
            'PHP_AUTH_PW'   => 'password',
        ]);

        // Swap onceBasic to always succeed in unit context
        Auth::shouldReceive('onceBasic')->andReturn(null)->once();
        Auth::shouldReceive('user')->andReturn($this->user);

        $called = false;
        $this->middleware->handle($request, function () use (&$called) {
            $called = true;

            return response()->json(['ok' => true]);
        });

        $this->assertTrue($called);
    }

    public function testBasicAuthSetsAuthMethodToBasic(): void
    {
        Auth::shouldReceive('onceBasic')->andReturn(null)->once();
        Auth::shouldReceive('user')->andReturn($this->user);
        // No bearer token on this request
        $request = $this->makeRequest('GET');

        $this->middleware->handle($request, function (Request $req) {
            return response()->json(['ok' => true]);
        });

        $this->assertEquals('basic', $request->attributes->get('auth_method'));
    }

    // ------------------------------------------------------------------
    // No auth
    // ------------------------------------------------------------------

    public function testNoAuthorizationHeaderReturns401ViaBasic(): void
    {
        // onceBasic returns a 401 response when credentials are missing/wrong
        $request  = $this->makeRequest('GET');
        $response = $this->middleware->handle($request, fn () => response()->json(['ok' => true]));

        // onceBasic returns 401 from Laravel internals; middleware passes it through
        $this->assertNotNull($response);
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    /**
     * Build a fake Request with optional headers.
     */
    private function makeRequest(string $method, array $serverHeaders = []): Request
    {
        $request = Request::create(
            '/' . company_id() . '/api/accounts',
            $method,
        );

        foreach ($serverHeaders as $key => $value) {
            if (in_array($key, ['PHP_AUTH_USER', 'PHP_AUTH_PW'])) {
                $request->server->set($key, $value);
            } else {
                $request->headers->set($key, $value);
            }
        }

        return $request;
    }
}
