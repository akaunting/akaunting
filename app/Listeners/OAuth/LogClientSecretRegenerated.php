<?php

namespace App\Listeners\OAuth;

use App\Events\OAuth\ClientSecretRegenerated;
use App\Models\OAuth\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogClientSecretRegenerated
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\OAuth\ClientSecretRegenerated  $event
     * @return void
     */
    public function handle(ClientSecretRegenerated $event): void
    {
        $client = $event->client;

        ActivityLog::logActivity([
            'company_id'   => $client->company_id,
            'user_id'      => $client->user_id ?? Auth::id(),
            'event_type'   => 'client.secret.regenerated',
            'resource_type' => 'client',
            'resource_id'  => $client->id,
            'client_name'  => $client->name,
            'client_id'    => $client->id,
            'description'  => trans('oauth.activity.client_secret_regenerated', [
                'name' => $client->name,
            ]),
        ]);
    }
}
