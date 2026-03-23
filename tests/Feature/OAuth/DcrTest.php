<?php

namespace Tests\Feature\OAuth;

use App\Models\OAuth\AccessToken;
use App\Models\OAuth\Client;
use App\Models\OAuth\RefreshToken;
use Illuminate\Support\Str;

/**
 * Dynamic Client Registration — RFC 7591 & RFC 7592
 * Controller: App\Http\Controllers\OAuth\ClientRegistration
 * Routes:
 *   POST   /oauth/register              → oauth.register
 *   GET    /oauth/register/{client_id}  → oauth.register.show
 *   PUT    /oauth/register/{client_id}  → oauth.register.update
 *   DELETE /oauth/register/{client_id}  → oauth.register.destroy
 */
class DcrTest extends OAuthTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'oauth.dcr.enable_management' => true,
            'oauth.dcr.allowed_domains'   => [],
        ]);
    }

    // ------------------------------------------------------------------
    // REGISTER (POST) — confidential client
    // ------------------------------------------------------------------

    public function testItShouldRegisterConfidentialClient(): void
    {
        $response = $this->postJson(route('oauth.register'), $this->dcrPayload())
            ->assertStatus(201)
            ->assertJsonStructure([
                'client_id',
                'client_secret',
                'client_secret_expires_at',
                'client_name',
                'redirect_uris',
                'grant_types',
                'response_types',
                'token_endpoint_auth_method',
            ]);

        $this->assertEquals(0, $response->json('client_secret_expires_at'));
        $this->assertDatabaseHas('oauth_clients', [
            'id' => $response->json('client_id'),
        ]);
    }

    // ------------------------------------------------------------------
    // REGISTER (POST) — public client (PKCE)
    // ------------------------------------------------------------------

    public function testItShouldRegisterPublicClient(): void
    {
        $response = $this->postJson(route('oauth.register'), $this->dcrPayload([
            'token_endpoint_auth_method' => 'none',
        ]))
            ->assertStatus(201);

        $this->assertArrayNotHasKey('client_secret', $response->json());

        $this->assertDatabaseHas('oauth_clients', [
            'id'     => $response->json('client_id'),
            'secret' => null,
        ]);
    }

    // ------------------------------------------------------------------
    // REGISTER — with management token
    // ------------------------------------------------------------------

    public function testItShouldReturnRegistrationAccessToken(): void
    {
        $response = $this->postJson(route('oauth.register'), $this->dcrPayload())
            ->assertStatus(201);

        $this->assertNotNull($response->json('registration_access_token'));
        $this->assertNotNull($response->json('registration_client_uri'));
    }

    // ------------------------------------------------------------------
    // REGISTER — invalid redirect URI (non-HTTPS in production)
    // ------------------------------------------------------------------

    public function testItShouldRejectHttpRedirectUriInProduction(): void
    {
        $this->withExceptionHandling();

        $response = $this->postJson(route('oauth.register'), $this->dcrPayload([
            'redirect_uris' => ['http://production.example.com/callback'],
        ]))
            ->assertStatus(400)
            ->assertJson(['error' => 'invalid_client_metadata']);
    }

    // ------------------------------------------------------------------
    // REGISTER — allows localhost HTTP
    // ------------------------------------------------------------------

    public function testItShouldAllowLocalhostHttp(): void
    {
        $this->postJson(route('oauth.register'), $this->dcrPayload([
            'redirect_uris' => ['http://localhost/callback'],
        ]))
            ->assertStatus(201);
    }

    // ------------------------------------------------------------------
    // REGISTER — domain allowlist
    // ------------------------------------------------------------------

    public function testItShouldRejectUnallowedDomain(): void
    {
        config(['oauth.dcr.allowed_domains' => ['allowed.example.com']]);

        $this->postJson(route('oauth.register'), $this->dcrPayload([
            'redirect_uris' => ['https://notallowed.example.com/callback'],
        ]))
            ->assertStatus(400)
            ->assertJson(['error' => 'invalid_client_metadata']);
    }

    public function testItShouldAcceptAllowedDomain(): void
    {
        config(['oauth.dcr.allowed_domains' => ['allowed.example.com']]);

        $this->postJson(route('oauth.register'), $this->dcrPayload([
            'redirect_uris' => ['https://allowed.example.com/callback'],
        ]))
            ->assertStatus(201);
    }

    // ------------------------------------------------------------------
    // GET (RFC 7592 - read)
    // ------------------------------------------------------------------

    public function testItShouldReadClientMetadata(): void
    {
        [$client, $registrationToken] = $this->registerAndGetToken();

        $this->getJson(route('oauth.register.show', $client->id), [
            'Authorization' => 'Bearer ' . $registrationToken,
        ])
            ->assertStatus(200)
            ->assertJsonStructure([
                'client_id', 'client_name', 'redirect_uris',
                'grant_types', 'response_types', 'token_endpoint_auth_method',
            ]);
    }

    public function testItShouldReturn401WithInvalidRegistrationToken(): void
    {
        [$client] = $this->registerAndGetToken();

        $this->getJson(route('oauth.register.show', $client->id), [
            'Authorization' => 'Bearer invalid-token-here',
        ])
            ->assertStatus(401)
            ->assertJson(['error' => 'invalid_token']);
    }

    public function testItShouldReturn401WhenManagementDisabled(): void
    {
        config(['oauth.dcr.enable_management' => false]);

        [$client, $registrationToken] = $this->registerAndGetToken();

        $this->getJson(route('oauth.register.show', $client->id), [
            'Authorization' => 'Bearer ' . $registrationToken,
        ])
            ->assertStatus(401);
    }

    // ------------------------------------------------------------------
    // PUT (RFC 7592 - update)
    // ------------------------------------------------------------------

    public function testItShouldUpdateClientMetadata(): void
    {
        [$client, $registrationToken] = $this->registerAndGetToken();

        $this->putJson(route('oauth.register.update', $client->id), [
            'client_name'   => 'Updated Client Name',
            'redirect_uris' => ['https://updated.example.com/callback'],
        ], [
            'Authorization' => 'Bearer ' . $registrationToken,
        ])
            ->assertStatus(200)
            ->assertJson(['client_name' => 'Updated Client Name']);

        $this->assertDatabaseHas('oauth_clients', [
            'id'   => $client->id,
            'name' => 'Updated Client Name',
        ]);
    }

    // ------------------------------------------------------------------
    // DELETE (RFC 7592 - delete)
    // ------------------------------------------------------------------

    public function testItShouldDeleteRegisteredClient(): void
    {
        [$client, $registrationToken] = $this->registerAndGetToken();

        // Create tokens to verify cascade delete
        $token = AccessToken::factory()->forClient($client->id)->create([
            'company_id' => company_id(),
            'user_id'    => $this->user->id,
        ]);

        $this->deleteJson(route('oauth.register.destroy', $client->id), [], [
            'Authorization' => 'Bearer ' . $registrationToken,
        ])
            ->assertStatus(204);

        $this->assertDatabaseMissing('oauth_clients', ['id' => $client->id]);
        $this->assertDatabaseMissing('oauth_access_tokens', ['id' => $token->id]);
    }

    // ------------------------------------------------------------------
    // Response headers
    // ------------------------------------------------------------------

    public function testRegisterResponseHasNoCacheHeader(): void
    {
        $response = $this->postJson(route('oauth.register'), $this->dcrPayload());

        $this->assertEquals('no-store', $response->headers->get('Cache-Control'));
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    /**
     * Register a client and return [$client, $registrationToken]
     */
    private function registerAndGetToken(): array
    {
        $response = $this->postJson(route('oauth.register'), $this->dcrPayload())
            ->assertStatus(201);

        $clientId          = $response->json('client_id');
        $registrationToken = $response->json('registration_access_token');

        $client = Client::find($clientId);

        return [$client, $registrationToken];
    }
}
