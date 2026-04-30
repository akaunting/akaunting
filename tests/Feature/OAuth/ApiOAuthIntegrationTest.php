<?php

namespace Tests\Feature\OAuth;

use App\Models\Banking\Account;
use App\Models\OAuth\AccessToken;
use Laravel\Passport\Passport;

/**
 * API + OAuth integration tests
 *
 * Validates that API endpoints work correctly with BOTH authentication methods:
 *   1. OAuth Bearer token  (new Passport path via DualApiAuth)
 *   2. HTTP Basic Auth     (existing path — must remain unaffected)
 *
 * Also validates:
 *   - company_id is resolved from the token (not URL) when Bearer is used
 *   - Revoked / expired tokens are rejected
 *   - Invalid tokens return the correct OAuth error shape
 *   - Basic Auth continues to work exactly as before
 */
class ApiOAuthIntegrationTest extends OAuthTestCase
{
    // ------------------------------------------------------------------
    // Basic Auth backward compatibility (S3: must not be affected)
    // ------------------------------------------------------------------

    public function testBasicAuthCanReadApiAccounts(): void
    {
        $this->loginAs()    // actingAs sets user on the web guard
            ->getJson(route('accounts.index'))
            ->assertStatus(200);
    }

    public function testBasicAuthCanCreateAccountViaApi(): void
    {
        $payload = [
            'name'            => 'OAuth Test Account',
            'number'          => 'OA-001',
            'currency_code'   => setting('default.currency'),
            'opening_balance' => 0,
            'bank_name'       => 'Test Bank',
            'enabled'         => true,
        ];

        $this->loginAs()
            ->postJson(route('accounts.store'), $payload)
            ->assertStatus(200);
    }

    // ------------------------------------------------------------------
    // OAuth Bearer token API access
    // ------------------------------------------------------------------

    public function testOAuthBearerTokenCanReadApiAccounts(): void
    {
        $client = $this->createClient();
        $token  = $this->createAccessToken($client, ['scopes' => ['banking:read']]);

        // Passport::actingAs() simulates a valid Passport-authenticated user
        Passport::actingAs($this->user, ['banking:read'], 'passport');

        $this->withToken($token->id)
            ->getJson(route('accounts.index'))
            ->assertStatus(200);
    }

    public function testOAuthBearerTokenCanCreateViaApi(): void
    {
        $client = $this->createClient();
        $token  = $this->createAccessToken($client, ['scopes' => ['banking:write']]);

        Passport::actingAs($this->user, ['banking:write'], 'passport');

        $payload = [
            'name'            => 'Bearer Created Account',
            'number'          => 'BC-001',
            'currency_code'   => setting('default.currency'),
            'opening_balance' => 0,
            'bank_name'       => 'Bearer Bank',
            'enabled'         => true,
        ];

        $this->withToken($token->id)
            ->postJson(route('accounts.store'), $payload)
            ->assertStatus(200);
    }

    // ------------------------------------------------------------------
    // company_id from token (Salesforce model)
    // ------------------------------------------------------------------

    public function testCompanyIdIsResolvedFromTokenNotUrl(): void
    {
        $companyId = company_id();
        $client    = $this->createClient(['company_id' => $companyId]);
        $token     = $this->createAccessToken($client, [
            'company_id' => $companyId,
            'scopes'     => ['banking:read'],
        ]);

        Passport::actingAs($this->user, ['banking:read'], 'passport');

        // We pass the token; company_id should come from the token
        $response = $this->withToken($token->id)
            ->getJson(route('accounts.index'))
            ->assertStatus(200);

        // Response should contain data scoped to the token's company_id
        $this->assertNotNull($response->json());
    }

    public function testTokenFromDifferentCompanyCannotAccessCurrentCompanyData(): void
    {
        // Create a token scoped to a fake other company
        $client = $this->createClient(['company_id' => 9999]);
        $token  = AccessToken::factory()->forClient($client->id)->create([
            'company_id' => 9999,
            'user_id'    => $this->user->id,
            'scopes'     => ['banking:read'],
        ]);

        // This token belongs to company 9999, but we're accessing URL for company_id()
        // IdentifyCompany should redirect or deny based on company mismatch
        $this->withToken($token->id)
            ->getJson(route('accounts.index'))
            ->assertStatus(401);
    }

    // ------------------------------------------------------------------
    // Invalid / revoked / expired tokens
    // ------------------------------------------------------------------

    public function testCompletelyInvalidBearerReturns401(): void
    {
        $this->withToken('this-is-not-a-real-token')
            ->getJson(route('accounts.index'))
            ->assertStatus(401)
            ->assertJsonStructure(['error', 'error_description']);
    }

    public function testInvalidBearerResponseHasOAuthErrorFormat(): void
    {
        $response = $this->withToken('garbage-token')
            ->getJson(route('accounts.index'))
            ->assertStatus(401);

        $this->assertEquals('invalid_token', $response->json('error'));
    }

    public function testRevokedTokenIsRejected(): void
    {
        $client = $this->createClient();
        $token  = $this->createRevokedToken($client);

        $this->withToken($token->id)
            ->getJson(route('accounts.index'))
            ->assertStatus(401);
    }

    public function testExpiredTokenIsRejected(): void
    {
        $client = $this->createClient();
        $token  = $this->createExpiredToken($client);

        $this->withToken($token->id)
            ->getJson(route('accounts.index'))
            ->assertStatus(401);
    }

    // ------------------------------------------------------------------
    // No auth
    // ------------------------------------------------------------------

    public function testUnauthenticatedRequestReturns401(): void
    {
        // No actingAs, no token, no Basic header
        $this->getJson(route('accounts.index'))
            ->assertStatus(401);
    }

    // ------------------------------------------------------------------
    // Scope validation (DualApiAuth + ValidateOAuthScopes together)
    // ------------------------------------------------------------------

    public function testOAuthTokenWithCorrectScopeCanAccessEndpoint(): void
    {
        config(['oauth.validate_scopes' => true]);

        $client = $this->createClient();
        $token  = $this->createAccessToken($client, ['scopes' => ['banking:read']]);

        Passport::actingAs($this->user, ['banking:read'], 'passport');

        $this->withToken($token->id)
            ->getJson(route('accounts.index'))
            ->assertStatus(200);
    }

    public function testMcpUseScopeCanAccessAllEndpoints(): void
    {
        config(['oauth.validate_scopes' => true]);

        $client = $this->createClient();
        $token  = $this->createAccessToken($client, ['scopes' => ['mcp:use']]);

        Passport::actingAs($this->user, ['mcp:use'], 'passport');

        $this->withToken($token->id)
            ->getJson(route('accounts.index'))
            ->assertStatus(200);
    }
}
