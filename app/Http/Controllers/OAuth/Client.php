<?php

namespace App\Http\Controllers\OAuth;

use App\Abstracts\Http\Controller;
use App\Http\Requests\OAuth\ClientRequest;
use App\Jobs\OAuth\CreateClient;
use App\Jobs\OAuth\DeleteClient;
use App\Jobs\OAuth\RegenerateClientSecret;
use App\Jobs\OAuth\UpdateClient;
use App\Models\OAuth\Client as ClientModel;
use Illuminate\Http\Request;

class Client extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
        $this->middleware('permission:create-oauth-clients')->only('create', 'store');
        $this->middleware('permission:read-oauth-clients')->only('index', 'show');
        $this->middleware('permission:update-oauth-clients')->only('edit', 'update', 'secret');
        $this->middleware('permission:delete-oauth-clients')->only('destroy');
    }

    /**
     * Display a listing of the clients.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = ClientModel::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->response('oauth.clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new client.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return $this->response('oauth.clients.create');
    }

    /**
     * Store a newly created client.
     *
     * @param  \App\Http\Requests\OAuth\ClientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientRequest $request)
    {
        $response = $this->ajaxDispatch(new CreateClient($request));

        if ($response->getData(true)['success']) {
            $response->setData(array_merge($response->getData(true), [
                'redirect' => route('oauth.clients.index'),
            ]));
        }

        return $response;
    }

    /**
     * Show the form for editing the specified client.
     *
     * @param  \App\Models\OAuth\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientModel $client)
    {
        return $this->response('oauth.clients.edit', compact('client'));
    }

    /**
     * Update the specified client.
     *
     * @param  \App\Models\OAuth\Client  $client
     * @param  \App\Http\Requests\OAuth\ClientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(ClientModel $client, ClientRequest $request)
    {
        $response = $this->ajaxDispatch(new UpdateClient($client, $request));

        if ($response->getData(true)['success']) {
            $response->setData(array_merge($response->getData(true), [
                'redirect' => route('oauth.clients.index'),
            ]));
        }

        return $response;
    }

    /**
     * Remove the specified client.
     *
     * @param  \App\Models\OAuth\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClientModel $client)
    {
        $response = $this->ajaxDispatch(new DeleteClient($client));

        if ($response->getData(true)['success']) {
            $response->setData(array_merge($response->getData(true), [
                'redirect' => route('oauth.clients.index'),
            ]));
        }

        return $response;
    }

    /**
     * Regenerate the client secret.
     *
     * @param  \App\Models\OAuth\Client  $client
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function secret(ClientModel $client, Request $request)
    {
        $response = $this->ajaxDispatch(new RegenerateClientSecret($client, $request));

        if ($response->getData(true)['success']) {
            $data = $response->getData(true);
            $response->setData(array_merge($data, [
                'data' => ['secret' => $data['data']['plain_secret'] ?? null],
            ]));
        }

        return $response;
    }
}
