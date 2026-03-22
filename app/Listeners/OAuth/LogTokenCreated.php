<?php

namespace App\Listeners\OAuth;

use App\Events\OAuth\TokenCreated;
use App\Models\OAuth\ActivityLog;
use Illuminate\Support\Facades\Auth;

class LogTokenCreated
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\OAuth\TokenCreated  $event
     * @return void
     */
    public function handle(TokenCreated $event): void
    {
        $token  = $event->token;
        $client = $event->client;

        ActivityLog::logActivity([
            'company_id'   => session('company_id') ?? company_id(),
            'user_id'      => $token->user_id ?? Auth::id(),
            'event_type'   => 'token.created',
            'resource_type' => 'token',
            'resource_id'  => $token->id ?? null,
            'client_name'  => $client?->name,
            'client_id'    => $client?->id ?? $token->client_id ?? null,
            'token_id'     => $token->id ?? null,
            'scopes'       => $token->scopes ?? [],
            'description'  => trans('oauth.activity.token_created', [
                'client' => $client?->name ?? 'Unknown Client',
            ]),
            'metadata'     => array_merge([
                'expires_at' => $token->expires_at ?? null,
                'grant_type' => $event->metadata['grant_type'] ?? null,
            ], $event->metadata),
        ]);
    }
}
