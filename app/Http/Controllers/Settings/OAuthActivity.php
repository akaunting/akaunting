<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Models\OAuth\ActivityLog;
use App\Models\OAuth\Client;
use Illuminate\Http\Request;

class OAuthActivity extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('permission:read-settings-oauth-activity');
    }

    /**
     * Display a listing of OAuth activity logs.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'company'])
            ->orderBy('created_at', 'desc');

        if ($request->has('event_type') && $request->event_type !== 'all') {
            $query->eventType($request->event_type);
        }

        if ($request->has('client_id') && $request->client_id) {
            $query->forClient($request->client_id);
        }

        if ($request->has('user_id') && $request->user_id) {
            $query->forUser($request->user_id);
        }

        if ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', $request->date_to);
        }

        if (! $request->has('date_from') && ! $request->has('date_to')) {
            $query->recent($request->get('days', 30));
        }

        $activities = $query->paginate($request->get('limit', 25));
        $eventTypes = $this->getEventTypeOptions();
        $clients    = $this->getClientOptions();

        return $this->response('settings.oauth.activity.index', compact('activities', 'eventTypes', 'clients'));
    }

    /**
     * Display the specified activity log.
     *
     * @param  \App\Models\OAuth\ActivityLog  $activity
     * @return \Illuminate\Http\Response
     */
    public function show(ActivityLog $activity)
    {
        $activity->load(['user', 'company']);

        return $this->response('settings.oauth.activity.show', compact('activity'));
    }

    /**
     * Get event type options for filter.
     *
     * @return array
     */
    protected function getEventTypeOptions(): array
    {
        return [
            'all' => trans('general.all'),
            'token.created' => trans('oauth.activity.events.token_created'),
            'token.revoked' => trans('oauth.activity.events.token_revoked'),
            'token.refreshed' => trans('oauth.activity.events.token_refreshed'),
            'client.created' => trans('oauth.activity.events.client_created'),
            'client.updated' => trans('oauth.activity.events.client_updated'),
            'client.deleted' => trans('oauth.activity.events.client_deleted'),
            'client.secret.regenerated' => trans('oauth.activity.events.client_secret_regenerated'),
            'authorization.approved' => trans('oauth.activity.events.authorization_approved'),
            'authorization.denied' => trans('oauth.activity.events.authorization_denied'),
        ];
    }

    /**
     * Get client options for filter.
     *
     * @return array
     */
    protected function getClientOptions(): array
    {
        $clients = Client::where('company_id', company_id())
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        return ['' => trans('general.all')] + $clients;
    }
}
