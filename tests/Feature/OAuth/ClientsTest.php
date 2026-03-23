<?php

namespace Tests\Feature\OAuth;

use App\Models\OAuth\AccessToken;
use App\Models\OAuth\Client;

/**
 * User-facing "Authorized Applications" controller
 * Controller: App\Http\Controllers\OAuth\Clients
 * Routes:     oauth.clients.index / show / revoke / destroy
 */
class ClientsTest extends OAuthTestCase
{
    // ------------------------------------------------------------------
    // INDEX — shows only clients owned or used by current user
    // ------------------------------------------------------------------

    public function testItShouldSeeAuthorizedClientsList(): void
    {
        $client = $this->createClient(['user_id' => $this->user->id]);

        $this->loginAs()
            ->get(route('oauth.clients.index'))
            ->assertStatus(200);
    }

    // ------------------------------------------------------------------
    // SHOW
    // ------------------------------------------------------------------

    public function testItShouldShowClientDetail(): void
    {
        $client = $this->createClient(['user_id' => $this->user->id]);

        $this->loginAs()
            ->get(route('oauth.clients.show', $client->id))
            ->assertStatus(200);
    }

    public function testItShouldReturn404ForUnrelatedClient(): void
    {
        $this->withExceptionHandling();

        // Create client owned by a different user (no tokens for current user)
        $other = $this->createClient(['user_id' => null]);

        $this->loginAs()
            ->get(route('oauth.clients.show', $other->id))
            ->assertStatus(404);
    }

    // ------------------------------------------------------------------
    // REVOKE — revokes all active tokens for a client
    // ------------------------------------------------------------------

    public function testItShouldRevokeTokensForClient(): void
    {
        $client = $this->createClient(['user_id' => $this->user->id]);
        $token  = $this->createAccessToken($client);

        $this->loginAs()
            ->post(route('oauth.clients.revoke', $client->id))
            ->assertRedirect(route('oauth.clients.index'));

        $this->assertDatabaseHas('oauth_access_tokens', [
            'id'      => $token->id,
            'revoked' => true,
        ]);
    }

    public function testRevokeReturnsSuccessFlashMessage(): void
    {
        $client = $this->createClient(['user_id' => $this->user->id]);
        $this->createAccessToken($client);

        $this->loginAs()
            ->post(route('oauth.clients.revoke', $client->id));

        $this->assertFlashLevel('success');
    }

    // ------------------------------------------------------------------
    // DESTROY — only dcr/dynamic clients can be deleted by users
    // ------------------------------------------------------------------

    public function testItShouldDeleteDynamicClient(): void
    {
        $client = $this->createClient([
            'user_id'  => $this->user->id,
            'provider' => 'dcr',
        ]);

        $this->loginAs()
            ->delete(route('oauth.clients.destroy', $client->id))
            ->assertRedirect(route('oauth.clients.index'));

        $this->assertSoftDeleted('oauth_clients', ['id' => $client->id]);
    }

    public function testItShouldReturn404WhenDeletingNonDynamicClient(): void
    {
        $this->withExceptionHandling();

        $client = $this->createClient([
            'user_id'  => $this->user->id,
            'provider' => null, // standard client, not dcr
        ]);

        $this->loginAs()
            ->delete(route('oauth.clients.destroy', $client->id))
            ->assertStatus(404);
    }

    // ------------------------------------------------------------------
    // Auth guard
    // ------------------------------------------------------------------

    public function testIndexRequiresAuthentication(): void
    {
        $this->withExceptionHandling();

        $this->get(route('oauth.clients.index'))
            ->assertRedirect();
    }
}
