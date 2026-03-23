<?php

namespace Tests\Feature\OAuth;

use App\Models\OAuth\AccessToken;
use App\Models\OAuth\Client;
use App\Models\OAuth\PersonalAccessClient;
use App\Models\OAuth\Scope;
use Illuminate\Support\Str;
use Tests\Feature\FeatureTestCase;

abstract class OAuthTestCase extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'oauth.enabled'       => true,
            'oauth.company_aware' => true,
            'oauth.auth_type'     => 'passport',
        ]);
    }

    // -----------------------------------------------------------------------
    // Client helpers
    // -----------------------------------------------------------------------

    protected function createClient(array $overrides = []): Client
    {
        return Client::factory()->create($overrides);
    }

    protected function createPublicClient(array $overrides = []): Client
    {
        return Client::factory()->public()->create($overrides);
    }

    protected function createPersonalAccessClient(): Client
    {
        $client = Client::factory()->personalAccess()->create([
            'company_id' => company_id(),
        ]);

        PersonalAccessClient::create([
            'company_id' => company_id(),
            'client_id'  => $client->id,
        ]);

        return $client;
    }

    // -----------------------------------------------------------------------
    // Token helpers
    // -----------------------------------------------------------------------

    protected function createAccessToken(Client $client, array $overrides = []): AccessToken
    {
        return AccessToken::factory()->forClient($client->id)->create([
            'company_id' => company_id(),
            'user_id'    => $this->user->id,
            ...$overrides,
        ]);
    }

    protected function createExpiredToken(Client $client): AccessToken
    {
        return AccessToken::factory()->forClient($client->id)->expired()->create([
            'company_id' => company_id(),
            'user_id'    => $this->user->id,
        ]);
    }

    protected function createRevokedToken(Client $client): AccessToken
    {
        return AccessToken::factory()->forClient($client->id)->revoked()->create([
            'company_id' => company_id(),
            'user_id'    => $this->user->id,
        ]);
    }

    // -----------------------------------------------------------------------
    // Scope helpers
    // -----------------------------------------------------------------------

    protected function createScope(array $overrides = []): Scope
    {
        return Scope::factory()->create($overrides);
    }

    // -----------------------------------------------------------------------
    // DCR helper
    // -----------------------------------------------------------------------

    protected function dcrPayload(array $overrides = []): array
    {
        return array_merge([
            'client_name'   => 'Test DCR Client ' . Str::random(5),
            'redirect_uris' => ['https://example.com/callback'],
            'grant_types'   => ['authorization_code'],
            'response_types'=> ['code'],
            'token_endpoint_auth_method' => 'client_secret_post',
        ], $overrides);
    }
}
