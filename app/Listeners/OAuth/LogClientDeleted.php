<?php

namespace App\Listeners\OAuth;

use App\Events\OAuth\ClientDeleted;
use App\Models\OAuth\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogClientDeleted
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\OAuth\ClientDeleted  $event
     * @return void
     */
    public function handle(ClientDeleted $event): void
    {
        $client = $event->client;

        ActivityLog::logActivity([
            'company_id'   => $client->company_id,
            'user_id'      => $client->user_id ?? Auth::id(),
            'event_type'   => 'client.deleted',
            'resource_type' => 'client',
            'resource_id'  => $client->id,
            'client_name'  => $client->name,
            'client_id'    => $client->id,
            'description'  => trans('oauth.activity.client_deleted', [
                'name' => $client->name,
            ]),
        ]);
    }
}
