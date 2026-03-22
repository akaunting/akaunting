<?php

namespace App\Listeners\OAuth;

use App\Events\OAuth\ClientCreated;
use App\Models\OAuth\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogClientCreated
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\OAuth\ClientCreated  $event
     * @return void
     */
    public function handle(ClientCreated $event): void
    {
        $client = $event->client;

        ActivityLog::logActivity([
            'company_id'   => $client->company_id,
            'user_id'      => $client->user_id ?? Auth::id(),
            'event_type'   => 'client.created',
            'resource_type' => 'client',
            'resource_id'  => $client->id,
            'client_name'  => $client->name,
            'client_id'    => $client->id,
            'description'  => trans('oauth.activity.client_created', [
                'name' => $client->name,
            ]),
            'metadata'     => [
                'confidential'           => $event->hasSecret,
                'redirect'               => $client->redirect,
                'personal_access_client' => $client->personal_access_client,
                'password_client'        => $client->password_client,
            ],
        ]);
    }
}
