<?php

namespace App\Listeners\OAuth;

use App\Events\OAuth\ClientUpdated;
use App\Models\OAuth\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogClientUpdated
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\OAuth\ClientUpdated  $event
     * @return void
     */
    public function handle(ClientUpdated $event): void
    {
        $client = $event->client;

        ActivityLog::logActivity([
            'company_id'   => $client->company_id,
            'user_id'      => $client->user_id ?? Auth::id(),
            'event_type'   => 'client.updated',
            'resource_type' => 'client',
            'resource_id'  => $client->id,
            'client_name'  => $client->name,
            'client_id'    => $client->id,
            'description'  => trans('oauth.activity.client_updated', [
                'name' => $client->name,
            ]),
            'metadata'     => [
                'changes'  => $client->getChanges(),
                'original' => $event->original,
            ],
        ]);
    }
}
