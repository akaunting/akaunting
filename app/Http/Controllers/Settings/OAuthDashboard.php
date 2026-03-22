<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Traits\OAuth\Statistics;
use Illuminate\Http\Request;

class OAuthDashboard extends Controller
{
    use Statistics;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('permission:read-settings-oauth-dashboard');
    }

    /**
     * Display OAuth statistics dashboard.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $days = $request->get('days', 30);

        // Overview Statistics
        $stats = [
            'total_clients' => $this->getTotalClients(),
            'active_tokens' => $this->getTotalActiveTokens(),
            'recent_activity' => $this->getRecentActivityCount(7),
            'tokens_created' => $this->getTokensCreatedRecently($days),
            'tokens_revoked' => $this->getTokensRevokedRecently($days),
        ];

        // Charts Data
        $activityTrend = $this->getDailyActivityTrend($days);
        $activityBreakdown = $this->getActivityBreakdown($days);
        $tokenExpirationStats = $this->getTokenExpirationStats();
        $clientTypeDistribution = $this->getClientTypeDistribution();

        // Top Lists
        $mostActiveClients = $this->getMostActiveClients(5, $days);
        $topUsers = $this->getTopUsersByTokens(5);

        return view('settings.oauth.dashboard.index', compact(
            'stats',
            'activityTrend',
            'activityBreakdown',
            'tokenExpirationStats',
            'clientTypeDistribution',
            'mostActiveClients',
            'topUsers',
            'days'
        ));
    }
}
