<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Http\Requests\OAuth\ScopeRequest;
use App\Jobs\OAuth\CreateScope;
use App\Jobs\OAuth\DeleteScope;
use App\Jobs\OAuth\UpdateScope;
use App\Models\OAuth\Scope;
use Illuminate\Http\Request;

class OAuthScopes extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('permission:read-settings-oauth')->only(['index']);
        $this->middleware('permission:create-settings-oauth')->only(['create', 'store']);
        $this->middleware('permission:update-settings-oauth')->only(['edit', 'update', 'enable', 'disable']);
        $this->middleware('permission:delete-settings-oauth')->only(['destroy']);
    }

    /**
     * Display a listing of the scopes.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $scopes = Scope::ordered()->get();

        $groups = Scope::select('group')
            ->distinct()
            ->whereNotNull('group')
            ->orderBy('group')
            ->pluck('group')
            ->toArray();

        return $this->response('settings.oauth.scopes.index', compact('scopes', 'groups'));
    }

    /**
     * Show the form for creating a new scope.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = $this->getGroupOptions();

        return $this->response('settings.oauth.scopes.create', compact('groups'));
    }

    /**
     * Store a newly created scope.
     *
     * @param  \App\Http\Requests\OAuth\ScopeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ScopeRequest $request)
    {
        $response = $this->ajaxDispatch(new CreateScope($request));

        if ($response->getData(true)['success']) {
            $response->setData(array_merge($response->getData(true), [
                'redirect' => route('settings.oauth.scopes.index'),
            ]));
        }

        return $response;
    }

    /**
     * Show the form for editing the specified scope.
     *
     * @param  \App\Models\OAuth\Scope  $scope
     * @return \Illuminate\Http\Response
     */
    public function edit(Scope $scope)
    {
        $groups = $this->getGroupOptions();

        return $this->response('settings.oauth.scopes.edit', compact('scope', 'groups'));
    }

    /**
     * Update the specified scope.
     *
     * @param  \App\Models\OAuth\Scope  $scope
     * @param  \App\Http\Requests\OAuth\ScopeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Scope $scope, ScopeRequest $request)
    {
        $response = $this->ajaxDispatch(new UpdateScope($scope, $request));

        if ($response->getData(true)['success']) {
            $response->setData(array_merge($response->getData(true), [
                'redirect' => route('settings.oauth.scopes.index'),
            ]));
        }

        return $response;
    }

    /**
     * Remove the specified scope.
     *
     * @param  \App\Models\OAuth\Scope  $scope
     * @return \Illuminate\Http\Response
     */
    public function destroy(Scope $scope)
    {
        $response = $this->ajaxDispatch(new DeleteScope($scope));

        if ($response->getData(true)['success']) {
            $response->setData(array_merge($response->getData(true), [
                'redirect' => route('settings.oauth.scopes.index'),
            ]));
        }

        return $response;
    }

    /**
     * Enable the specified scope.
     *
     * @param  \App\Models\OAuth\Scope  $scope
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function enable(Scope $scope, Request $request)
    {
        $request->merge(['enabled' => true]);

        return $this->ajaxDispatch(new UpdateScope($scope, $request));
    }

    /**
     * Disable the specified scope.
     *
     * @param  \App\Models\OAuth\Scope  $scope
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function disable(Scope $scope, Request $request)
    {
        $request->merge(['enabled' => false]);

        return $this->ajaxDispatch(new UpdateScope($scope, $request));
    }

    /**
     * Get group options for select box.
     *
     * @return array
     */
    protected function getGroupOptions()
    {
        return [
            'basic' => trans('oauth.scopes.group_basic'),
            'advanced' => trans('oauth.scopes.group_advanced'),
            'mcp' => trans('oauth.scopes.group_mcp'),
            'custom' => trans('oauth.scopes.group_custom'),
        ];
    }
}
