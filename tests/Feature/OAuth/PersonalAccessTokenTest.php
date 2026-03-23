<?php

namespace Tests\Feature\OAuth;

use App\Models\OAuth\AccessToken;
use App\Models\OAuth\PersonalAccessClient;

/**
 * Personal Access Token management
 * Controller: App\Http\Controllers\OAuth\PersonalAccessToken
 * Routes:     GET/POST/DELETE /oauth/personal-access-tokens
 */
class PersonalAccessTokenTest extends OAuthTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createPersonalAccessClient();
    }

    // ------------------------------------------------------------------
    // INDEX
    // ------------------------------------------------------------------

    public function testItShouldListPersonalTokens(): void
    {
        $response = $this->loginAs()
            ->getJson(route('oauth.personal-tokens.index'))
            ->assertStatus(200)
            ->assertJsonStructure(['*' => ['id', 'name', 'scopes', 'revoked']]);
    }

    public function testIndexOnlyShowsCurrentCompanyTokens(): void
    {
        // Token in current company
        $patClient = PersonalAccessClient::where('company_id', company_id())
            ->first()
            ->client;

        $token = $this->createAccessToken($patClient);

        $response = $this->loginAs()
            ->getJson(route('oauth.personal-tokens.index'));

        $ids = collect($response->json())->pluck('id');
        $this->assertContains($token->id, $ids->toArray());
    }

    // ------------------------------------------------------------------
    // STORE
    // ------------------------------------------------------------------

    public function testItShouldCreatePersonalAccessToken(): void
    {
        $response = $this->loginAs()
            ->postJson(route('oauth.personal-tokens.store'), [
                'name'   => 'My API Key',
                'scopes' => [],
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertNotNull($response->json('data.access_token'));
    }

    public function testCreatedTokenHasCorrectCompanyId(): void
    {
        $response = $this->loginAs()
            ->postJson(route('oauth.personal-tokens.store'), [
                'name'   => 'Company Token',
                'scopes' => [],
            ])
            ->assertStatus(200);

        $tokenId = $response->json('data.token.id');

        $this->assertDatabaseHas('oauth_access_tokens', [
            'id'         => $tokenId,
            'company_id' => company_id(),
        ]);
    }

    public function testItShouldCreateTokenWithScopes(): void
    {
        $scope = $this->createScope(['key' => 'mcp:use']);

        $response = $this->loginAs()
            ->postJson(route('oauth.personal-tokens.store'), [
                'name'   => 'Scoped Token',
                'scopes' => ['mcp:use'],
            ])
            ->assertStatus(200);

        $this->assertEquals(['mcp:use'], $response->json('data.token.scopes'));
    }

    // ------------------------------------------------------------------
    // STORE — validation
    // ------------------------------------------------------------------

    public function testItShouldFailWhenNameMissing(): void
    {
        $this->withExceptionHandling();

        $this->loginAs()
            ->postJson(route('oauth.personal-tokens.store'), [
                'scopes' => [],
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function testItShouldFailWhenScopesNotArray(): void
    {
        $this->withExceptionHandling();

        $this->loginAs()
            ->postJson(route('oauth.personal-tokens.store'), [
                'name'   => 'Bad Scopes',
                'scopes' => 'not-an-array',
            ])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['scopes']);
    }

    // ------------------------------------------------------------------
    // STORE — no PAC for company
    // ------------------------------------------------------------------

    public function testItReturns400WhenNoPACExistsForCompany(): void
    {
        // Remove personal access client
        PersonalAccessClient::where('company_id', company_id())->delete();

        $response = $this->loginAs()
            ->postJson(route('oauth.personal-tokens.store'), [
                'name'   => 'Orphan Token',
                'scopes' => [],
            ])
            ->assertStatus(400)
            ->assertJson(['success' => false]);
    }

    // ------------------------------------------------------------------
    // DESTROY
    // ------------------------------------------------------------------

    public function testItShouldRevokePersonalAccessToken(): void
    {
        $patClient = PersonalAccessClient::where('company_id', company_id())
            ->first()
            ->client;

        $token = $this->createAccessToken($patClient);

        $this->loginAs()
            ->deleteJson(route('oauth.personal-tokens.destroy', $token->id))
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('oauth_access_tokens', [
            'id'      => $token->id,
            'revoked' => true,
        ]);
    }

    public function testItShouldReturn404ForOtherUsersToken(): void
    {
        $this->withExceptionHandling();

        // Token belonging to another user in a different company context
        $patClient = PersonalAccessClient::where('company_id', company_id())
            ->first()
            ->client;

        $token = AccessToken::factory()->forClient($patClient->id)->create([
            'company_id' => company_id(),
            'user_id'    => 9999, // not current user
        ]);

        $this->loginAs()
            ->deleteJson(route('oauth.personal-tokens.destroy', $token->id))
            ->assertStatus(404);
    }

    public function testItShouldReturn403ForCrossCompanyTokenDelete(): void
    {
        $this->withExceptionHandling();

        $patClient = PersonalAccessClient::where('company_id', company_id())
            ->first()
            ->client;

        // Create token with a different company_id
        $token = AccessToken::factory()->forClient($patClient->id)->create([
            'company_id' => 9999,
            'user_id'    => $this->user->id,
        ]);

        $this->loginAs()
            ->deleteJson(route('oauth.personal-tokens.destroy', $token->id))
            ->assertStatus(403);
    }

    // ------------------------------------------------------------------
    // Auth guard
    // ------------------------------------------------------------------

    public function testRequiresAuthentication(): void
    {
        $this->withExceptionHandling();

        $this->postJson(route('oauth.personal-tokens.store'), ['name' => 'x'])
            ->assertStatus(401);
    }
}
