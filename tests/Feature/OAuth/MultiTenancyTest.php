<?php

namespace Tests\Feature\OAuth;

use App\Jobs\Common\CreateCompany;
use App\Models\OAuth\AccessToken;
use App\Models\OAuth\Client;
use App\Models\OAuth\Scope;

/**
 * Company isolation (multi-tenancy) across all OAuth resources
 */
class MultiTenancyTest extends OAuthTestCase
{
    // ------------------------------------------------------------------
    // Client isolation
    // ------------------------------------------------------------------

    public function testClientBelongsToCorrectCompany(): void
    {
        $this->loginAs()
            ->post(route('oauth.clients.store'), [
                'name'         => 'Tenant App',
                'redirect'     => 'https://example.com/callback',
                'confidential' => true,
            ]);

        $this->assertDatabaseHas('oauth_clients', [
            'name'       => 'Tenant App',
            'company_id' => company_id(),
        ]);
    }

    public function testGlobalScopeFiltersClientsToCurrentCompany(): void
    {
        $company1 = company_id();

        // Create client in current company
        $clientInCompany = $this->createClient(['company_id' => $company1]);

        // Create client in a fake second company (bypassing global scope)
        Client::withoutGlobalScope('company')->create([
            'name'                   => 'Other Company Client',
            'secret'                 => null,
            'redirect'               => 'https://other.example.com',
            'personal_access_client' => false,
            'password_client'        => false,
            'revoked'                => false,
            'company_id'             => 9999,
        ]);

        $clients = Client::all();

        // Global scope: only company1 client visible
        $this->assertTrue($clients->contains('id', $clientInCompany->id));
        $this->assertFalse($clients->contains('company_id', 9999));
    }

    public function testAllCompaniesQueryBypasses(): void
    {
        $clientInOtherCompany = Client::withoutGlobalScope('company')->create([
            'name'                   => 'All Companies Client',
            'secret'                 => null,
            'redirect'               => 'https://other.example.com',
            'personal_access_client' => false,
            'password_client'        => false,
            'revoked'                => false,
            'company_id'             => 9999,
        ]);

        $all = Client::allCompanies()->where('id', $clientInOtherCompany->id)->first();

        $this->assertNotNull($all);
        $this->assertEquals(9999, $all->company_id);
    }

    // ------------------------------------------------------------------
    // Token isolation
    // ------------------------------------------------------------------

    public function testTokenGlobalScopeFiltersToCurrentCompany(): void
    {
        $client = $this->createClient();

        // Token in current company
        $myToken = $this->createAccessToken($client);

        // Token in another company (bypass scope)
        $otherToken = AccessToken::factory()->forClient($client->id)->create([
            'company_id' => 9999,
            'user_id'    => $this->user->id,
        ]);

        $tokens = AccessToken::all();

        $this->assertTrue($tokens->contains('id', $myToken->id));
        $this->assertFalse($tokens->contains('id', $otherToken->id));
    }

    public function testTokenIndexOnlyReturnsCurrentCompanyTokens(): void
    {
        $client = $this->createClient();

        $myToken    = $this->createAccessToken($client);
        $otherToken = AccessToken::factory()->forClient($client->id)->create([
            'company_id' => 9999,
            'user_id'    => $this->user->id,
        ]);

        $response = $this->loginAs()
            ->getJson(route('oauth.tokens.index'))
            ->assertStatus(200);

        $ids = collect($response->json())->pluck('id');

        $this->assertContains($myToken->id, $ids->toArray());
        $this->assertNotContains($otherToken->id, $ids->toArray());
    }

    // ------------------------------------------------------------------
    // Introspect isolation
    // ------------------------------------------------------------------

    public function testIntrospectReturnsFalseForOtherCompanyToken(): void
    {
        $client = $this->createClient();

        // Token belongs to another company
        $token = AccessToken::factory()->forClient($client->id)->create([
            'company_id' => 9999,
            'user_id'    => $this->user->id,
        ]);

        $this->loginAs()
            ->postJson(route('oauth.token.introspect'), ['token' => $token->id])
            ->assertJson(['active' => false]);
    }

    // ------------------------------------------------------------------
    // Client model boot: auto-sets company_id
    // ------------------------------------------------------------------

    public function testClientModelAutoSetsCompanyIdOnCreate(): void
    {
        $client = Client::factory()->create();

        $this->assertEquals(company_id(), $client->company_id);
    }

    // ------------------------------------------------------------------
    // Scope isolation (scopes are global, not company-scoped)
    // ------------------------------------------------------------------

    public function testScopesAreGlobalNotCompanyScoped(): void
    {
        // Scopes don't have company_id — they're global
        $scope = $this->createScope();

        $this->assertDatabaseHas('oauth_scopes', ['id' => $scope->id]);

        // Any company can see scopes
        $found = Scope::find($scope->id);
        $this->assertNotNull($found);
    }

    // ------------------------------------------------------------------
    // DCR: company_id from session when company_aware
    // ------------------------------------------------------------------

    public function testDcrClientGetsCompanyIdFromSession(): void
    {
        $response = $this->loginAs()
            ->postJson(route('oauth.register'), $this->dcrPayload())
            ->assertStatus(201);

        $clientId = $response->json('client_id');

        $this->assertDatabaseHas('oauth_clients', [
            'id'         => $clientId,
            'company_id' => company_id(),
        ]);
    }
}
