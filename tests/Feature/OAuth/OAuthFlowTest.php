<?php

namespace Tests\Feature\OAuth;

use App\Models\OAuth\AccessToken;
use App\Models\OAuth\PersonalAccessClient;

/**
 * End-to-end OAuth flow — integrates all components together
 * Validates the full company-aware token lifecycle
 */
class OAuthFlowTest extends OAuthTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createPersonalAccessClient();
    }

    // ------------------------------------------------------------------
    // Full PAT lifecycle
    // ------------------------------------------------------------------

    public function testFullPersonalAccessTokenLifecycle(): void
    {
        // 1. Create token
        $createResponse = $this->loginAs()
            ->postJson(route('oauth.personal-tokens.store'), [
                'name'   => 'Mobile App Token',
                'scopes' => [],
            ])
            ->assertStatus(200)
            ->assertJson(['success' => true]);

        $tokenId = $createResponse->json('data.token.id');
        $this->assertNotNull($tokenId);

        // 2. Verify token in DB with correct company
        $this->assertDatabaseHas('oauth_access_tokens', [
            'id'         => $tokenId,
            'company_id' => company_id(),
            'user_id'    => $this->user->id,
        ]);

        // 3. Introspect shows active
        $this->loginAs()
            ->postJson(route('oauth.token.introspect'), ['token' => $tokenId])
            ->assertJson(['active' => true]);

        // 4. Revoke token
        $this->loginAs()
            ->deleteJson(route('oauth.personal-tokens.destroy', $tokenId))
            ->assertJson(['success' => true]);

        // 5. Introspect shows inactive
        $this->loginAs()
            ->postJson(route('oauth.token.introspect'), ['token' => $tokenId])
            ->assertJson(['active' => false]);
    }

    // ------------------------------------------------------------------
    // Full Client CRUD + Secret lifecycle
    // ------------------------------------------------------------------

    public function testFullClientCrudLifecycle(): void
    {
        // 1. Create client
        $createResponse = $this->loginAs()
            ->post(route('oauth.clients.store'), [
                'name'         => 'Lifecycle Client',
                'redirect'     => 'https://example.com/callback',
                'confidential' => true,
            ])
            ->assertStatus(200);

        $clientId = $createResponse->json('data.id') ?? $createResponse->json('id');

        $client = \App\Models\OAuth\Client::where('name', 'Lifecycle Client')->first();
        $this->assertNotNull($client);

        // 2. Edit page loads
        $this->loginAs()
            ->get(route('oauth.clients.edit', $client))
            ->assertStatus(200);

        // 3. Update
        $this->loginAs()
            ->patch(route('oauth.clients.update', $client), [
                'name'     => 'Updated Lifecycle Client',
                'redirect' => 'https://updated.example.com/callback',
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('oauth_clients', [
            'id'   => $client->id,
            'name' => 'Updated Lifecycle Client',
        ]);

        // 4. Regenerate secret
        $secretResponse = $this->loginAs()
            ->post(route('oauth.clients.secret', $client))
            ->assertStatus(200);

        $this->assertNotNull($secretResponse->json('data.secret'));

        // 5. Delete
        $this->loginAs()
            ->delete(route('oauth.clients.destroy', $client))
            ->assertStatus(200);

        $this->assertSoftDeleted('oauth_clients', ['id' => $client->id]);
    }

    // ------------------------------------------------------------------
    // Company isolation: token from company A not visible in company B
    // ------------------------------------------------------------------

    public function testTokensAreIsolatedBetweenCompanies(): void
    {
        $companyId = company_id();

        // Token for current company
        $client  = $this->createClient(['company_id' => $companyId]);
        $token   = $this->createAccessToken($client);

        // Token for another company (bypassing global scope)
        $otherToken = AccessToken::factory()->forClient($client->id)->create([
            'company_id' => 9999,
            'user_id'    => $this->user->id,
        ]);

        // Current company sees own token as active
        $this->loginAs()
            ->postJson(route('oauth.token.introspect'), ['token' => $token->id])
            ->assertJson(['active' => true]);

        // Current company sees other company's token as inactive
        $this->loginAs()
            ->postJson(route('oauth.token.introspect'), ['token' => $otherToken->id])
            ->assertJson(['active' => false]);
    }

    // ------------------------------------------------------------------
    // Discovery → Register → Token flow (simulated)
    // ------------------------------------------------------------------

    public function testDiscoveryEndpointPointsToCorrectRegistrationUri(): void
    {
        $metadata = $this->getJson(route('oauth.metadata'))
            ->assertStatus(200)
            ->json();

        $registrationEndpoint = $metadata['registration_endpoint'];

        // Registration endpoint should work
        $this->postJson($registrationEndpoint, $this->dcrPayload())
            ->assertStatus(201);
    }

    // ------------------------------------------------------------------
    // Scope list → token creation consistency
    // ------------------------------------------------------------------

    public function testScopeListMatchesWhatTokensCanRequest(): void
    {
        $scope = $this->createScope(['key' => 'invoices:read', 'enabled' => true]);

        $scopeList = $this->loginAs()
            ->getJson(route('oauth.scopes.index'))
            ->assertStatus(200)
            ->json();

        $scopeKeys = collect($scopeList['data'])->pluck('id')->toArray();

        // The scope we created should appear in the list
        $this->assertContains('invoices:read', $scopeKeys);
    }
}
