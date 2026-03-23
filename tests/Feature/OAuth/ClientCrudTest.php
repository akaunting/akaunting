<?php

namespace Tests\Feature\OAuth;

use App\Jobs\OAuth\CreateClient;
use App\Jobs\OAuth\DeleteClient;
use App\Jobs\OAuth\UpdateClient;
use App\Models\OAuth\Client;
use Illuminate\Support\Facades\Hash;

/**
 * Admin-facing OAuth Client CRUD
 * Controller: App\Http\Controllers\OAuth\Client
 * Routes:     oauth.clients.*  (GET/POST/PUT/PATCH/DELETE /oauth/clients/…)
 */
class ClientCrudTest extends OAuthTestCase
{
    // ------------------------------------------------------------------
    // INDEX
    // ------------------------------------------------------------------

    public function testItShouldSeeClientListPage(): void
    {
        $this->loginAs()
            ->get(route('oauth.clients.index'))
            ->assertStatus(200);
    }

    // ------------------------------------------------------------------
    // CREATE
    // ------------------------------------------------------------------

    public function testItShouldSeeClientCreatePage(): void
    {
        $this->loginAs()
            ->get(route('oauth.clients.create'))
            ->assertStatus(200);
    }

    // ------------------------------------------------------------------
    // STORE — confidential client
    // ------------------------------------------------------------------

    public function testItShouldCreateConfidentialClient(): void
    {
        $payload = [
            'name'         => 'My API App',
            'redirect'     => 'https://myapp.example.com/callback',
            'confidential' => true,
        ];

        $this->loginAs()
            ->post(route('oauth.clients.store'), $payload)
            ->assertStatus(200);

        $this->assertDatabaseHas('oauth_clients', [
            'name'    => 'My API App',
            'company_id' => company_id(),
        ]);
    }

    // ------------------------------------------------------------------
    // STORE — public client (PKCE)
    // ------------------------------------------------------------------

    public function testItShouldCreatePublicClient(): void
    {
        $payload = [
            'name'         => 'My SPA App',
            'redirect'     => 'https://spa.example.com/callback',
            'confidential' => false,
        ];

        $this->loginAs()
            ->post(route('oauth.clients.store'), $payload)
            ->assertStatus(200);

        $this->assertDatabaseHas('oauth_clients', [
            'name'   => 'My SPA App',
            'secret' => null,
        ]);
    }

    // ------------------------------------------------------------------
    // STORE — multi-URL redirect
    // ------------------------------------------------------------------

    public function testItShouldCreateClientWithMultipleRedirectUrls(): void
    {
        $payload = [
            'name'         => 'Multi Redirect App',
            'redirect'     => "https://app.example.com/callback\nhttps://app.example.com/callback2",
            'confidential' => true,
        ];

        $this->loginAs()
            ->post(route('oauth.clients.store'), $payload)
            ->assertStatus(200);

        $this->assertDatabaseHas('oauth_clients', ['name' => 'Multi Redirect App']);
    }

    // ------------------------------------------------------------------
    // STORE — validation: name required
    // ------------------------------------------------------------------

    public function testItShouldFailStoreWhenNameMissing(): void
    {
        $this->withExceptionHandling();

        $this->loginAs()
            ->post(route('oauth.clients.store'), [
                'redirect' => 'https://example.com/callback',
            ])
            ->assertStatus(422);
    }

    // ------------------------------------------------------------------
    // STORE — validation: redirect required
    // ------------------------------------------------------------------

    public function testItShouldFailStoreWhenRedirectMissing(): void
    {
        $this->withExceptionHandling();

        $this->loginAs()
            ->post(route('oauth.clients.store'), [
                'name' => 'No Redirect',
            ])
            ->assertStatus(422);
    }

    // ------------------------------------------------------------------
    // EDIT
    // ------------------------------------------------------------------

    public function testItShouldSeeClientEditPage(): void
    {
        $client = $this->createClient();

        $this->loginAs()
            ->get(route('oauth.clients.edit', $client))
            ->assertStatus(200)
            ->assertSee($client->name);
    }

    // ------------------------------------------------------------------
    // UPDATE
    // ------------------------------------------------------------------

    public function testItShouldUpdateClient(): void
    {
        $client = $this->createClient();

        $this->loginAs()
            ->patch(route('oauth.clients.update', $client), [
                'name'     => 'Updated Client Name',
                'redirect' => 'https://updated.example.com/cb',
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('oauth_clients', [
            'id'   => $client->id,
            'name' => 'Updated Client Name',
        ]);
    }

    // ------------------------------------------------------------------
    // UPDATE — validation: name required
    // ------------------------------------------------------------------

    public function testItShouldFailUpdateWhenNameMissing(): void
    {
        $this->withExceptionHandling();

        $client = $this->createClient();

        $this->loginAs()
            ->patch(route('oauth.clients.update', $client), [
                'name'     => '',
                'redirect' => 'https://example.com/callback',
            ])
            ->assertStatus(422);
    }

    // ------------------------------------------------------------------
    // DESTROY
    // ------------------------------------------------------------------

    public function testItShouldDeleteClient(): void
    {
        $client = $this->createClient();

        $this->loginAs()
            ->delete(route('oauth.clients.destroy', $client))
            ->assertStatus(200);

        $this->assertSoftDeleted('oauth_clients', ['id' => $client->id]);
    }

    // ------------------------------------------------------------------
    // SECRET — regenerate
    // ------------------------------------------------------------------

    public function testItShouldRegenerateClientSecret(): void
    {
        $client = $this->createClient();
        $oldSecret = $client->secret;

        $response = $this->loginAs()
            ->post(route('oauth.clients.secret', $client))
            ->assertStatus(200);

        $plain = $response->json('data.secret');
        $this->assertNotNull($plain);

        // DB secret changed
        $this->assertDatabaseMissing('oauth_clients', [
            'id'     => $client->id,
            'secret' => $oldSecret,
        ]);
    }

    // ------------------------------------------------------------------
    // SECRET — verifies new bcrypt secret is valid
    // ------------------------------------------------------------------

    public function testRegeneratedSecretVerifiesCorrectly(): void
    {
        config(['oauth.hash_client_secrets' => true]);

        $client = $this->createClient();

        $response = $this->loginAs()
            ->post(route('oauth.clients.secret', $client))
            ->assertStatus(200);

        $plain = $response->json('data.secret');
        $fresh = $client->fresh();

        $this->assertTrue(Hash::check($plain, $fresh->secret));
    }

    // ------------------------------------------------------------------
    // Company isolation: client belongs to own company
    // ------------------------------------------------------------------

    public function testCreatedClientBelongsToCurrentCompany(): void
    {
        $this->loginAs()
            ->post(route('oauth.clients.store'), [
                'name'         => 'Company-scoped App',
                'redirect'     => 'https://example.com/callback',
                'confidential' => true,
            ])
            ->assertStatus(200);

        $this->assertDatabaseHas('oauth_clients', [
            'name'       => 'Company-scoped App',
            'company_id' => company_id(),
        ]);
    }
}
