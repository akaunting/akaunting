<?php

namespace Tests\Feature\OAuth;

use App\Models\OAuth\AccessToken;
use App\Models\OAuth\RefreshToken;
use Illuminate\Support\Str;

/**
 * Token introspect (RFC 7662) and revoke (RFC 7009)
 * Controller: App\Http\Controllers\OAuth\Token
 * Routes:     POST /oauth/token/introspect   → oauth.token.introspect
 *             POST /oauth/token/revoke       → oauth.token.revoke
 *             GET  /oauth/tokens             → oauth.tokens.index
 *             DELETE /oauth/tokens/{id}      → oauth.tokens.destroy
 */
class TokenTest extends OAuthTestCase
{
    // ------------------------------------------------------------------
    // INTROSPECT — active token
    // ------------------------------------------------------------------

    public function testIntrospectReturnsActiveForValidToken(): void
    {
        $client = $this->createClient();
        $token  = $this->createAccessToken($client);

        $this->loginAs()
            ->postJson(route('oauth.token.introspect'), ['token' => $token->id])
            ->assertStatus(200)
            ->assertJson([
                'active'     => true,
                'user_id'    => $this->user->id,
                'company_id' => company_id(),
                'token_type' => 'Bearer',
            ]);
    }

    // ------------------------------------------------------------------
    // INTROSPECT — required fields in active response
    // ------------------------------------------------------------------

    public function testIntrospectActiveResponseHasRequiredFields(): void
    {
        $client = $this->createClient();
        $token  = $this->createAccessToken($client, ['scopes' => ['mcp:use']]);

        $response = $this->loginAs()
            ->postJson(route('oauth.token.introspect'), ['token' => $token->id]);

        $response->assertJsonStructure([
            'active', 'scope', 'client_id', 'user_id',
            'company_id', 'token_type', 'exp', 'iat', 'nbf', 'sub',
        ]);

        $this->assertEquals('mcp:use', $response->json('scope'));
    }

    // ------------------------------------------------------------------
    // INTROSPECT — revoked token
    // ------------------------------------------------------------------

    public function testIntrospectReturnsInactiveForRevokedToken(): void
    {
        $client = $this->createClient();
        $token  = $this->createRevokedToken($client);

        $this->loginAs()
            ->postJson(route('oauth.token.introspect'), ['token' => $token->id])
            ->assertStatus(200)
            ->assertJson(['active' => false]);
    }

    // ------------------------------------------------------------------
    // INTROSPECT — expired token
    // ------------------------------------------------------------------

    public function testIntrospectReturnsInactiveForExpiredToken(): void
    {
        $client = $this->createClient();
        $token  = $this->createExpiredToken($client);

        $this->loginAs()
            ->postJson(route('oauth.token.introspect'), ['token' => $token->id])
            ->assertStatus(200)
            ->assertJson(['active' => false]);
    }

    // ------------------------------------------------------------------
    // INTROSPECT — unknown token
    // ------------------------------------------------------------------

    public function testIntrospectReturnsInactiveForUnknownToken(): void
    {
        $this->loginAs()
            ->postJson(route('oauth.token.introspect'), ['token' => 'totally-fake-token'])
            ->assertStatus(200)
            ->assertJson(['active' => false]);
    }

    // ------------------------------------------------------------------
    // INTROSPECT — cross-company token (should be inactive)
    // ------------------------------------------------------------------

    public function testIntrospectReturnsInactiveForCrossCompanyToken(): void
    {
        $client = $this->createClient();
        $token  = AccessToken::factory()->forClient($client->id)->create([
            'company_id' => 9999, // different company
            'user_id'    => $this->user->id,
        ]);

        $this->loginAs()
            ->postJson(route('oauth.token.introspect'), ['token' => $token->id])
            ->assertStatus(200)
            ->assertJson(['active' => false]);
    }

    // ------------------------------------------------------------------
    // INTROSPECT — validation: token required
    // ------------------------------------------------------------------

    public function testIntrospectRequiresTokenField(): void
    {
        $this->withExceptionHandling();

        $this->loginAs()
            ->postJson(route('oauth.token.introspect'), [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['token']);
    }

    // ------------------------------------------------------------------
    // REVOKE — access token
    // ------------------------------------------------------------------

    public function testItShouldRevokeAccessToken(): void
    {
        $client = $this->createClient();
        $token  = $this->createAccessToken($client);

        $this->loginAs()
            ->postJson(route('oauth.token.revoke'), [
                'token'            => $token->id,
                'token_type_hint'  => 'access_token',
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('oauth_access_tokens', [
            'id'      => $token->id,
            'revoked' => true,
        ]);
    }

    // ------------------------------------------------------------------
    // REVOKE — refresh token
    // ------------------------------------------------------------------

    public function testItShouldRevokeRefreshToken(): void
    {
        $client = $this->createClient();
        $token  = $this->createAccessToken($client);

        $refreshToken = RefreshToken::create([
            'id'              => Str::random(100),
            'access_token_id' => $token->id,
            'revoked'         => false,
            'expires_at'      => now()->addDay(),
        ]);

        $this->loginAs()
            ->postJson(route('oauth.token.revoke'), [
                'token'           => $refreshToken->id,
                'token_type_hint' => 'refresh_token',
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('oauth_refresh_tokens', [
            'id'      => $refreshToken->id,
            'revoked' => true,
        ]);
    }

    // ------------------------------------------------------------------
    // REVOKE — RFC 7009: always returns 200, even for unknown token
    // ------------------------------------------------------------------

    public function testRevokeAlwaysReturns200PerRfc7009(): void
    {
        $this->loginAs()
            ->postJson(route('oauth.token.revoke'), [
                'token' => 'nonexistent-token-id',
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);
    }

    // ------------------------------------------------------------------
    // REVOKE — cross-company token (silent success per RFC 7009)
    // ------------------------------------------------------------------

    public function testRevokeCrossCompanyTokenSilentlySucceeds(): void
    {
        $client = $this->createClient();
        $token  = AccessToken::factory()->forClient($client->id)->create([
            'company_id' => 9999,
            'user_id'    => $this->user->id,
        ]);

        $this->loginAs()
            ->postJson(route('oauth.token.revoke'), ['token' => $token->id])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        // Token should NOT be revoked since it belongs to another company
        $this->assertDatabaseHas('oauth_access_tokens', [
            'id'      => $token->id,
            'revoked' => false,
        ]);
    }

    // ------------------------------------------------------------------
    // REVOKE — validation
    // ------------------------------------------------------------------

    public function testRevokeRequiresTokenField(): void
    {
        $this->withExceptionHandling();

        $this->loginAs()
            ->postJson(route('oauth.token.revoke'), [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['token']);
    }

    // ------------------------------------------------------------------
    // TOKEN LIST (web view)
    // ------------------------------------------------------------------

    public function testItShouldSeeTokensPage(): void
    {
        $this->loginAs()
            ->get(route('oauth.tokens.index'))
            ->assertStatus(200);
    }

    // ------------------------------------------------------------------
    // TOKEN DESTROY (web)
    // ------------------------------------------------------------------

    public function testItShouldDestroyTokenViaWebRoute(): void
    {
        $client = $this->createClient();
        $token  = $this->createAccessToken($client);

        $this->loginAs()
            ->deleteJson(route('oauth.tokens.destroy', $token->id))
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('oauth_access_tokens', [
            'id'      => $token->id,
            'revoked' => true,
        ]);
    }

    public function testItShouldReturn404DestroyForOtherUsersToken(): void
    {
        $this->withExceptionHandling();

        $client = $this->createClient();
        $token  = AccessToken::factory()->forClient($client->id)->create([
            'company_id' => company_id(),
            'user_id'    => 9999,
        ]);

        $this->loginAs()
            ->deleteJson(route('oauth.tokens.destroy', $token->id))
            ->assertStatus(404);
    }
}
