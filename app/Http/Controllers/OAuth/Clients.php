<?php

namespace App\Http\Controllers\OAuth;

use App\Abstracts\Http\Controller;
use App\Events\OAuth\TokenRevoked;
use App\Jobs\OAuth\DeleteClient;
use App\Models\OAuth\AccessToken;
use App\Models\OAuth\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Clients extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-oauth-clients|read-auth-profile')->only('index', 'show');
        $this->middleware('permission:update-oauth-clients|update-auth-profile')->only('revoke');
        $this->middleware('permission:delete-oauth-clients|delete-auth-profile')->only('destroy');
    }

    /**
     * Display a listing of user's OAuth clients.
     */
    public function index()
    {
        $user = auth()->user();

        $clients = Client::where('user_id', $user->id)
            ->orWhere(function ($query) use ($user) {
                $query->whereHas('tokens', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->with(['tokens' => function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('revoked', false)
                      ->latest();
            }])
            ->latest()
            ->paginate(20);

        return $this->response('oauth.clients.index', compact('clients'));
    }

    /**
     * Show detailed information about an OAuth client.
     */
    public function show($id)
    {
        $user = auth()->user();

        $client = Client::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereHas('tokens', function ($q) use ($user) {
                          $q->where('user_id', $user->id);
                      });
            })
            ->firstOrFail();

        $activeTokens = AccessToken::where('client_id', $client->id)
            ->where('user_id', $user->id)
            ->where('revoked', false)
            ->where('expires_at', '>', now())
            ->with('scopes')
            ->latest()
            ->get();

        $revokedTokens = AccessToken::where('client_id', $client->id)
            ->where('user_id', $user->id)
            ->where(function ($query) {
                $query->where('revoked', true)
                      ->orWhere('expires_at', '<=', now());
            })
            ->latest()
            ->limit(10)
            ->get();

        return $this->response('oauth.clients.show', compact('client', 'activeTokens', 'revokedTokens'));
    }

    /**
     * Revoke all active tokens for a client.
     */
    public function revoke(Request $request, $id)
    {
        $user = auth()->user();

        $client = Client::where('id', $id)
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhereHas('tokens', function ($q) use ($user) {
                          $q->where('user_id', $user->id);
                      });
            })
            ->firstOrFail();

        // Revoke all active tokens for this client for the current user
        $tokens = AccessToken::where('client_id', $client->id)
            ->where('user_id', $user->id)
            ->where('revoked', false)
            ->get();

        $revokedCount = 0;

        DB::transaction(function () use ($tokens, $client, $user, &$revokedCount) {
            foreach ($tokens as $token) {
                $token->revoked = true;
                $token->save();
                $revokedCount++;
            }
        });

        // Fire events after successful transaction
        foreach ($tokens as $token) {
            event(new TokenRevoked($token->id, $client->id, $user->id));
        }

        $message = trans('oauth.access_revoked', [
            'name' => $client->name,
            'count' => $revokedCount,
        ]);

        flash($message)->success();

        return redirect()->route('oauth.clients.index');
    }

    /**
     * Delete a dynamically registered client.
     */
    public function destroy($id)
    {
        $user = auth()->user();

        $client = Client::where('id', $id)
            ->where('user_id', $user->id)
            ->whereIn('provider', [null, 'dcr']) // Only allow deletion of dynamic clients
            ->firstOrFail();

        $response = $this->ajaxDispatch(new DeleteClient($client));

        $message = trans('oauth.client_deleted', ['name' => $client->name]);

        flash($message)->success();

        return redirect()->route('oauth.passport.clients.index');
    }
}
