<?php

namespace App\Listeners\OAuth;

use App\Events\OAuth\AuthorizationDenied;
use App\Models\OAuth\ActivityLog;

class LogAuthorizationDenied
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\OAuth\AuthorizationDenied  $event
     * @return void
     */
    public function handle(AuthorizationDenied $event): void
    {
        ActivityLog::logActivity([
            'company_id'   => session('company_id') ?? company_id(),
            'user_id'      => $event->user->id,
            'event_type'   => 'authorization.denied',
            'resource_type' => 'authorization',
            'client_name'  => $event->client->name,
            'client_id'    => $event->client->id,
            'description'  => trans('oauth.activity.authorization_denied', [
                'client' => $event->client->name,
            ]),
        ]);
    }
}
