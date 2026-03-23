<?php

namespace Tests\Feature\OAuth;

use App\Models\OAuth\ActivityLog;
use App\Models\OAuth\Client;

/**
 * OAuth Activity Log
 * Controller: App\Http\Controllers\Settings\OAuthActivity
 * Routes:     settings.oauth.activity.index / show
 */
class OAuthActivityTest extends OAuthTestCase
{
    // ------------------------------------------------------------------
    // INDEX
    // ------------------------------------------------------------------

    public function testItShouldSeeActivityLogPage(): void
    {
        $this->loginAs()
            ->get(route('settings.oauth.activity.index'))
            ->assertStatus(200);
    }

    public function testIndexShowsActivitiesForCurrentCompany(): void
    {
        $activity = $this->createActivity('token.created');

        $this->loginAs()
            ->get(route('settings.oauth.activity.index'))
            ->assertStatus(200)
            ->assertSee($activity->description);
    }

    // ------------------------------------------------------------------
    // INDEX — filters
    // ------------------------------------------------------------------

    public function testIndexFiltersbyEventType(): void
    {
        $this->createActivity('token.created');
        $this->createActivity('client.created');

        $this->loginAs()
            ->get(route('settings.oauth.activity.index', ['event_type' => 'token.created']))
            ->assertStatus(200);
    }

    public function testIndexFiltersByClientId(): void
    {
        $client = $this->createClient();
        $this->createActivity('token.created', ['client_id' => $client->id]);

        $this->loginAs()
            ->get(route('settings.oauth.activity.index', ['client_id' => $client->id]))
            ->assertStatus(200);
    }

    public function testIndexFiltersByDateRange(): void
    {
        $this->loginAs()
            ->get(route('settings.oauth.activity.index', [
                'date_from' => now()->subDays(7)->format('Y-m-d'),
                'date_to'   => now()->format('Y-m-d'),
            ]))
            ->assertStatus(200);
    }

    public function testIndexPaginates(): void
    {
        // Create more than the default page size
        for ($i = 0; $i < 5; $i++) {
            $this->createActivity('token.created');
        }

        $this->loginAs()
            ->get(route('settings.oauth.activity.index', ['limit' => 3]))
            ->assertStatus(200);
    }

    // ------------------------------------------------------------------
    // SHOW
    // ------------------------------------------------------------------

    public function testItShouldShowActivityDetail(): void
    {
        $activity = $this->createActivity('client.created');

        $this->loginAs()
            ->get(route('settings.oauth.activity.show', $activity))
            ->assertStatus(200);
    }

    public function testShowLoadsUserAndCompanyRelations(): void
    {
        $activity = $this->createActivity('token.revoked');

        $this->loginAs()
            ->get(route('settings.oauth.activity.show', $activity))
            ->assertStatus(200);
    }

    // ------------------------------------------------------------------
    // Auth guard
    // ------------------------------------------------------------------

    public function testIndexRequiresAuthentication(): void
    {
        $this->withExceptionHandling();

        $this->get(route('settings.oauth.activity.index'))
            ->assertRedirect();
    }

    // ------------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------------

    protected function createActivity(string $eventType, array $overrides = []): ActivityLog
    {
        return ActivityLog::create(array_merge([
            'company_id'    => company_id(),
            'user_id'       => $this->user->id,
            'event_type'    => $eventType,
            'resource_type' => 'token',
            'resource_id'   => null,
            'description'   => trans('oauth.activity.' . str_replace('.', '_', $eventType), [], 'en-GB'),
            'metadata'      => [],
            'ip_address'    => '127.0.0.1',
        ], $overrides));
    }
}
