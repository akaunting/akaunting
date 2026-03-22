<?php

namespace App\Listeners\OAuth;

use App\Events\OAuth\TokenRevoked;
use App\Models\OAuth\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogTokenRevoked
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\OAuth\TokenRevoked  $event
     * @return void
     */
    public function handle(TokenRevoked $event): void
    {
        ActivityLog::logActivity([
            'company_id'   => session('company_id') ?? company_id(),
            'user_id'      => $event->userId ?? Auth::id(),
            'event_type'   => 'token.revoked',
            'resource_type' => 'token',
            'client_id'    => $event->clientId,
            'token_id'     => $event->tokenId,
            'description'  => trans('oauth.activity.token_revoked'),
            'metadata'     => $event->metadata,
        ]);
    }
}
