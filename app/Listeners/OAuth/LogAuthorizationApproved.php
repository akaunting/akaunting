<?php

namespace App\Listeners\OAuth;

use App\Events\OAuth\AuthorizationApproved;
use App\Models\OAuth\ActivityLog;

class LogAuthorizationApproved
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\OAuth\AuthorizationApproved  $event
     * @return void
     */
    public function handle(AuthorizationApproved $event): void
    {
        ActivityLog::logActivity([
            'company_id'   => session('company_id') ?? company_id(),
            'user_id'      => $event->user->id,
            'event_type'   => 'authorization.approved',
            'resource_type' => 'authorization',
            'client_name'  => $event->client->name,
            'client_id'    => $event->client->id,
            'scopes'       => $event->scopes,
            'description'  => trans('oauth.activity.authorization_approved', [
                'client' => $event->client->name,
            ]),
        ]);
    }
}
