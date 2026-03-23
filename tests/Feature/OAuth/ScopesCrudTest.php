<?php

namespace Tests\Feature\OAuth;

use App\Jobs\OAuth\CreateScope;
use App\Models\OAuth\Scope;

/**
 * Settings > OAuth Scopes CRUD
 * Controller: App\Http\Controllers\Settings\OAuthScopes
 * Routes:     settings.oauth.scopes.*  (admin routes)
 */
class ScopesCrudTest extends OAuthTestCase
{
    // ------------------------------------------------------------------
    // INDEX
    // ------------------------------------------------------------------

    public function testItShouldSeeScopeListPage(): void
    {
        $this->loginAs()
            ->get(route('settings.oauth.scopes.index'))
            ->assertStatus(200);
    }

    public function testIndexShowsExistingScopes(): void
    {
        $scope = $this->createScope();

        $this->loginAs()
            ->get(route('settings.oauth.scopes.index'))
            ->assertStatus(200)
            ->assertSee($scope->name);
    }

    // ------------------------------------------------------------------
    // CREATE
    // ------------------------------------------------------------------

    public function testItShouldSeeScopeCreatePage(): void
    {
        $this->loginAs()
            ->get(route('settings.oauth.scopes.create'))
            ->assertStatus(200);
    }

    // ------------------------------------------------------------------
    // STORE
    // ------------------------------------------------------------------

    public function testItShouldCreateScope(): void
    {
        $payload = $this->getScopeRequest();

        $this->loginAs()
            ->post(route('settings.oauth.scopes.store'), $payload)
            ->assertStatus(200);

        $this->assertDatabaseHas('oauth_scopes', [
            'key'  => $payload['key'],
            'name' => $payload['name'],
        ]);
    }

    public function testItShouldCreateScopeWithGroup(): void
    {
        $payload = $this->getScopeRequest(['group' => 'mcp']);

        $this->loginAs()
            ->post(route('settings.oauth.scopes.store'), $payload)
            ->assertStatus(200);

        $this->assertDatabaseHas('oauth_scopes', [
            'key'   => $payload['key'],
            'group' => 'mcp',
        ]);
    }

    public function testItShouldCreateDefaultScope(): void
    {
        $payload = $this->getScopeRequest(['is_default' => true]);

        $this->loginAs()
            ->post(route('settings.oauth.scopes.store'), $payload)
            ->assertStatus(200);

        $this->assertDatabaseHas('oauth_scopes', [
            'key'        => $payload['key'],
            'is_default' => true,
        ]);
    }

    // ------------------------------------------------------------------
    // STORE — validation
    // ------------------------------------------------------------------

    public function testItShouldFailStoreWhenKeyMissing(): void
    {
        $this->withExceptionHandling();

        $payload = $this->getScopeRequest(['key' => '']);

        $this->loginAs()
            ->post(route('settings.oauth.scopes.store'), $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['key']);
    }

    public function testItShouldFailStoreWhenKeyHasInvalidFormat(): void
    {
        $this->withExceptionHandling();

        $payload = $this->getScopeRequest(['key' => 'INVALID KEY WITH SPACES']);

        $this->loginAs()
            ->post(route('settings.oauth.scopes.store'), $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['key']);
    }

    public function testItShouldFailStoreWhenKeyIsDuplicate(): void
    {
        $this->withExceptionHandling();

        $existing = $this->createScope(['key' => 'my:scope']);
        $payload  = $this->getScopeRequest(['key' => 'my:scope']);

        $this->loginAs()
            ->post(route('settings.oauth.scopes.store'), $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['key']);
    }

    public function testItShouldFailStoreWhenNameMissing(): void
    {
        $this->withExceptionHandling();

        $payload = $this->getScopeRequest(['name' => '']);

        $this->loginAs()
            ->post(route('settings.oauth.scopes.store'), $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    // ------------------------------------------------------------------
    // EDIT
    // ------------------------------------------------------------------

    public function testItShouldSeeScopeEditPage(): void
    {
        $scope = $this->createScope();

        $this->loginAs()
            ->get(route('settings.oauth.scopes.edit', $scope))
            ->assertStatus(200)
            ->assertSee($scope->name);
    }

    // ------------------------------------------------------------------
    // UPDATE
    // ------------------------------------------------------------------

    public function testItShouldUpdateScope(): void
    {
        $scope   = $this->createScope();
        $payload = $this->getScopeRequest(['key' => $scope->key, 'name' => 'Updated Name']);

        $this->loginAs()
            ->patch(route('settings.oauth.scopes.update', $scope), $payload)
            ->assertStatus(200);

        $this->assertDatabaseHas('oauth_scopes', [
            'id'   => $scope->id,
            'name' => 'Updated Name',
        ]);
    }

    public function testItShouldFailUpdateWhenKeyChangedToDuplicate(): void
    {
        $this->withExceptionHandling();

        $scope1 = $this->createScope(['key' => 'scope:one']);
        $scope2 = $this->createScope(['key' => 'scope:two']);

        $payload = $this->getScopeRequest(['key' => 'scope:one']);

        $this->loginAs()
            ->patch(route('settings.oauth.scopes.update', $scope2), $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['key']);
    }

    // ------------------------------------------------------------------
    // DESTROY
    // ------------------------------------------------------------------

    public function testItShouldDeleteScope(): void
    {
        $scope = $this->createScope();

        $this->loginAs()
            ->delete(route('settings.oauth.scopes.destroy', $scope))
            ->assertStatus(200);

        $this->assertSoftDeleted('oauth_scopes', ['id' => $scope->id]);
    }

    // ------------------------------------------------------------------
    // ENABLE
    // ------------------------------------------------------------------

    public function testItShouldEnableScope(): void
    {
        $scope = $this->createScope(['enabled' => false]);

        $this->loginAs()
            ->get(route('settings.oauth.scopes.enable', $scope))
            ->assertStatus(200);

        $this->assertDatabaseHas('oauth_scopes', [
            'id'      => $scope->id,
            'enabled' => true,
        ]);
    }

    // ------------------------------------------------------------------
    // DISABLE
    // ------------------------------------------------------------------

    public function testItShouldDisableScope(): void
    {
        $scope = $this->createScope(['enabled' => true]);

        $this->loginAs()
            ->get(route('settings.oauth.scopes.disable', $scope))
            ->assertStatus(200);

        $this->assertDatabaseHas('oauth_scopes', [
            'id'      => $scope->id,
            'enabled' => false,
        ]);
    }

    // ------------------------------------------------------------------
    // Scope key valid formats
    // ------------------------------------------------------------------

    public function testItShouldAcceptColonSeparatedKey(): void
    {
        $payload = $this->getScopeRequest(['key' => 'invoices:read']);

        $this->loginAs()
            ->post(route('settings.oauth.scopes.store'), $payload)
            ->assertStatus(200);
    }

    public function testItShouldAcceptHyphenatedKey(): void
    {
        $payload = $this->getScopeRequest(['key' => 'sales-read']);

        $this->loginAs()
            ->post(route('settings.oauth.scopes.store'), $payload)
            ->assertStatus(200);
    }

    public function testItShouldRejectUppercaseKey(): void
    {
        $this->withExceptionHandling();

        $payload = $this->getScopeRequest(['key' => 'UPPERCASE:KEY']);

        $this->loginAs()
            ->post(route('settings.oauth.scopes.store'), $payload)
            ->assertStatus(422);
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    protected function getScopeRequest(array $overrides = []): array
    {
        return array_merge([
            'key'         => 'test:' . strtolower($this->faker->unique()->word),
            'name'        => $this->faker->words(2, true),
            'description' => $this->faker->sentence,
            'group'       => 'custom',
            'enabled'     => true,
            'is_default'  => false,
            'sort_order'  => 10,
        ], $overrides);
    }
}
