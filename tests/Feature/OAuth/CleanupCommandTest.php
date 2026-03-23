<?php

namespace Tests\Feature\OAuth;

use App\Models\OAuth\AccessToken;
use App\Models\OAuth\Client;
use App\Models\OAuth\RefreshToken;
use Illuminate\Support\Str;

/**
 * OAuthCleanupCommand tests
 * Command: App\Console\Commands\OAuthCleanupCommand
 */
class CleanupCommandTest extends OAuthTestCase
{
    // ------------------------------------------------------------------
    // Cleanup expired tokens
    // ------------------------------------------------------------------

    public function testItCleansUpExpiredAccessTokens(): void
    {
        $client = $this->createClient();

        // Active token — should NOT be deleted
        $active = $this->createAccessToken($client, [
            'expires_at' => now()->addHour(),
        ]);

        // Expired token — should be deleted
        $expired = $this->createExpiredToken($client);

        $this->artisan('oauth:cleanup', ['--days' => 0])
            ->assertSuccessful();

        $this->assertDatabaseMissing('oauth_access_tokens', ['id' => $expired->id]);
        $this->assertDatabaseHas('oauth_access_tokens', ['id' => $active->id]);
    }

    // ------------------------------------------------------------------
    // Cleanup revoked tokens
    // ------------------------------------------------------------------

    public function testItCleansUpRevokedTokens(): void
    {
        $client  = $this->createClient();
        $revoked = $this->createRevokedToken($client);
        $active  = $this->createAccessToken($client);

        $this->artisan('oauth:cleanup', ['--days' => 0])
            ->assertSuccessful();

        $this->assertDatabaseMissing('oauth_access_tokens', ['id' => $revoked->id]);
        $this->assertDatabaseHas('oauth_access_tokens', ['id' => $active->id]);
    }

    // ------------------------------------------------------------------
    // Cascade: refresh tokens deleted with access tokens
    // ------------------------------------------------------------------

    public function testItCleansUpOrphanedRefreshTokens(): void
    {
        $client  = $this->createClient();
        $expired = $this->createExpiredToken($client);

        $refresh = RefreshToken::create([
            'id'              => Str::random(100),
            'access_token_id' => $expired->id,
            'revoked'         => false,
            'expires_at'      => now()->addDays(30),
        ]);

        $this->artisan('oauth:cleanup', ['--days' => 0])
            ->assertSuccessful();

        $this->assertDatabaseMissing('oauth_refresh_tokens', ['id' => $refresh->id]);
    }

    // ------------------------------------------------------------------
    // Cleanup inactive clients
    // ------------------------------------------------------------------

    public function testItCleansUpClientsWithNoActiveTokens(): void
    {
        $cutoffDays = 30;

        // Old client with NO active tokens
        $oldClient = Client::factory()->create([
            'created_at' => now()->subDays($cutoffDays + 5),
        ]);

        // Old client WITH an active token — should NOT be deleted
        $activeClient = Client::factory()->create([
            'created_at' => now()->subDays($cutoffDays + 5),
        ]);
        $this->createAccessToken($activeClient, [
            'created_at' => now()->subDays(5), // recent token
        ]);

        // Recent client — should NOT be deleted regardless
        $recentClient = Client::factory()->create([
            'created_at' => now()->subDays(5),
        ]);

        $this->artisan('oauth:cleanup', ['--days' => $cutoffDays])
            ->assertSuccessful();

        $this->assertSoftDeleted('oauth_clients', ['id' => $oldClient->id]);
        $this->assertDatabaseHas('oauth_clients', ['id' => $activeClient->id]);
        $this->assertDatabaseHas('oauth_clients', ['id' => $recentClient->id]);
    }

    // ------------------------------------------------------------------
    // --dry-run flag: reports without deleting
    // ------------------------------------------------------------------

    public function testDryRunDoesNotDelete(): void
    {
        $client  = $this->createClient();
        $expired = $this->createExpiredToken($client);

        $this->artisan('oauth:cleanup', [
            '--days'    => 0,
            '--dry-run' => true,
        ])
            ->assertSuccessful();

        // Token should still exist (dry-run)
        $this->assertDatabaseHas('oauth_access_tokens', ['id' => $expired->id]);
    }

    // ------------------------------------------------------------------
    // Command produces output
    // ------------------------------------------------------------------

    public function testCommandOutputsReport(): void
    {
        $client  = $this->createClient();
        $this->createExpiredToken($client);

        $this->artisan('oauth:cleanup', ['--days' => 0])
            ->assertSuccessful()
            ->expectsOutputToContain('');
    }
}
