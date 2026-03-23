<?php

namespace Tests\Unit\OAuth;

use App\Jobs\OAuth\CreateClient;
use App\Jobs\OAuth\CreateScope;
use App\Jobs\OAuth\DeleteClient;
use App\Jobs\OAuth\DeleteScope;
use App\Jobs\OAuth\RegenerateClientSecret;
use App\Jobs\OAuth\UpdateClient;
use App\Jobs\OAuth\UpdateScope;
use App\Models\OAuth\Client;
use App\Models\OAuth\Scope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tests\Feature\OAuth\OAuthTestCase;

/**
 * Unit tests for OAuth Jobs
 * Each job is tested in isolation: correct model mutation, events, transactions
 */
class JobsTest extends OAuthTestCase
{
    // ==================================================================
    // CreateClient
    // ==================================================================

    public function testCreateClientCreatesModel(): void
    {
        Event::fake();

        $request = new Request([
            'name'         => 'Unit Test Client',
            'redirect'     => 'https://unit.example.com/cb',
            'confidential' => true,
        ]);

        $model = $this->dispatch(new CreateClient($request));

        $this->assertInstanceOf(Client::class, $model);
        $this->assertEquals('Unit Test Client', $model->name);
        $this->assertNotNull($model->secret);
        $this->assertEquals(company_id(), $model->company_id);
    }

    public function testCreatePublicClientHasNoSecret(): void
    {
        Event::fake();

        $request = new Request([
            'name'         => 'Public Client',
            'redirect'     => 'https://spa.example.com/cb',
            'confidential' => false,
        ]);

        $model = $this->dispatch(new CreateClient($request));

        $this->assertNull($model->secret);
    }

    public function testCreateClientFiresClientCreatedEvent(): void
    {
        Event::fake(\App\Events\OAuth\ClientCreated::class);

        $request = new Request([
            'name'         => 'Event Test Client',
            'redirect'     => 'https://event.example.com/cb',
            'confidential' => true,
        ]);

        $this->dispatch(new CreateClient($request));

        Event::assertDispatched(\App\Events\OAuth\ClientCreated::class);
    }

    // ==================================================================
    // UpdateClient
    // ==================================================================

    public function testUpdateClientChangesNameAndRedirect(): void
    {
        Event::fake();

        $client = $this->createClient();

        $request = new Request([
            'name'     => 'Renamed Client',
            'redirect' => 'https://new.example.com/cb',
        ]);

        $updated = $this->dispatch(new UpdateClient($client, $request));

        $this->assertEquals('Renamed Client', $updated->name);
        $this->assertStringContainsString('new.example.com', $updated->redirect);
    }

    public function testUpdateClientFiresClientUpdatedEvent(): void
    {
        Event::fake(\App\Events\OAuth\ClientUpdated::class);

        $client  = $this->createClient();
        $request = new Request(['name' => 'Updated', 'redirect' => 'https://updated.example.com/cb']);

        $this->dispatch(new UpdateClient($client, $request));

        Event::assertDispatched(\App\Events\OAuth\ClientUpdated::class);
    }

    // ==================================================================
    // DeleteClient
    // ==================================================================

    public function testDeleteClientSoftDeletesModel(): void
    {
        Event::fake();

        $client = $this->createClient();

        $this->dispatch(new DeleteClient($client));

        $this->assertSoftDeleted('oauth_clients', ['id' => $client->id]);
    }

    public function testDeleteClientFiresClientDeletedEvent(): void
    {
        Event::fake(\App\Events\OAuth\ClientDeleted::class);

        $client = $this->createClient();
        $this->dispatch(new DeleteClient($client));

        Event::assertDispatched(\App\Events\OAuth\ClientDeleted::class);
    }

    // ==================================================================
    // RegenerateClientSecret
    // ==================================================================

    public function testRegenerateClientSecretChangesSecret(): void
    {
        Event::fake();

        $client    = $this->createClient();
        $oldSecret = $client->secret;

        $updated = $this->dispatch(new RegenerateClientSecret($client, new Request()));

        $this->assertNotEquals($oldSecret, $updated->secret);
    }

    public function testRegenerateClientSecretSetsBcryptWhenHashEnabled(): void
    {
        Event::fake();

        config(['oauth.hash_client_secrets' => true]);

        $client = $this->createClient();
        $updated = $this->dispatch(new RegenerateClientSecret($client, new Request()));

        $plainSecret = $updated->plain_secret;
        $this->assertTrue(Hash::check($plainSecret, $updated->fresh()->secret));
    }

    public function testRegenerateClientSecretStoresPlainSecretAsVirtualProperty(): void
    {
        Event::fake();

        $client  = $this->createClient();
        $updated = $this->dispatch(new RegenerateClientSecret($client, new Request()));

        $this->assertNotNull($updated->plain_secret);
        $this->assertEquals(40, strlen($updated->plain_secret));
    }

    public function testRegenerateClientSecretFiresEvent(): void
    {
        Event::fake(\App\Events\OAuth\ClientSecretRegenerated::class);

        $client = $this->createClient();
        $this->dispatch(new RegenerateClientSecret($client, new Request()));

        Event::assertDispatched(\App\Events\OAuth\ClientSecretRegenerated::class);
    }

    // ==================================================================
    // CreateScope
    // ==================================================================

    public function testCreateScopeCreatesModel(): void
    {
        $request = new Request([
            'key'         => 'invoices:read',
            'name'        => 'Read Invoices',
            'description' => 'View invoice data',
            'group'       => 'basic',
            'enabled'     => true,
            'is_default'  => false,
            'sort_order'  => 10,
        ]);

        $scope = $this->dispatch(new CreateScope($request));

        $this->assertInstanceOf(Scope::class, $scope);
        $this->assertEquals('invoices:read', $scope->key);
        $this->assertEquals('basic', $scope->group);
    }

    // ==================================================================
    // UpdateScope
    // ==================================================================

    public function testUpdateScopeChangesFields(): void
    {
        $scope = $this->createScope(['key' => 'contacts:read', 'enabled' => true]);

        $request = new Request([
            'key'     => 'contacts:read',
            'name'    => 'Updated Name',
            'enabled' => false,
        ]);

        $updated = $this->dispatch(new UpdateScope($scope, $request));

        $this->assertEquals('Updated Name', $updated->name);
        $this->assertFalse((bool) $updated->enabled);
    }

    // ==================================================================
    // DeleteScope
    // ==================================================================

    public function testDeleteScopeSoftDeletesModel(): void
    {
        $scope = $this->createScope();

        $this->dispatch(new DeleteScope($scope));

        $this->assertSoftDeleted('oauth_scopes', ['id' => $scope->id]);
    }

    // ==================================================================
    // DB::transaction wrapping — verify atomicity
    // ==================================================================

    public function testCreateClientIsWrappedInTransaction(): void
    {
        // If an exception occurs mid-create, nothing is persisted
        Event::fake();

        $countBefore = Client::count();

        try {
            $badRequest = new Request([
                'name'         => null, // will cause a DB constraint violation if reaches save
                'redirect'     => 'https://example.com/cb',
                'confidential' => true,
            ]);

            $this->dispatch(new CreateClient($badRequest));
        } catch (\Exception $e) {
            // swallow
        }

        // Count should remain the same (transaction rolled back)
        $this->assertEquals($countBefore, Client::count());
    }
}
