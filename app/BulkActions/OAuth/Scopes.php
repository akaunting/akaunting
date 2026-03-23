<?php

namespace App\BulkActions\OAuth;

use App\Abstracts\BulkAction;
use App\Jobs\OAuth\DeleteScope;
use App\Jobs\OAuth\UpdateScope;
use App\Models\OAuth\Scope;
use Illuminate\Http\Request;

class Scopes extends BulkAction
{
    public $model = Scope::class;

    public $text = 'oauth.scopes';

    public $path = [
        'group' => 'oauth',
        'type'  => 'scopes',
    ];

    public $actions = [
        'enable' => [
            'icon'       => 'check_circle',
            'name'       => 'general.enable',
            'message'    => 'bulk_actions.message.enable',
            'permission' => 'update-settings-oauth',
        ],
        'disable' => [
            'icon'       => 'hide_source',
            'name'       => 'general.disable',
            'message'    => 'bulk_actions.message.disable',
            'permission' => 'update-settings-oauth',
        ],
        'delete' => [
            'icon'       => 'delete',
            'name'       => 'general.delete',
            'message'    => 'bulk_actions.message.delete',
            'permission' => 'delete-settings-oauth-scopes',
        ],
    ];

    public function enable(Request $request)
    {
        $scopes = $this->getSelectedRecords($request);

        foreach ($scopes as $scope) {
            try {
                $this->dispatch(new UpdateScope($scope, $request->merge(['enabled' => true])));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function disable(Request $request)
    {
        $scopes = $this->getSelectedRecords($request);

        foreach ($scopes as $scope) {
            try {
                $this->dispatch(new UpdateScope($scope, $request->merge(['enabled' => false])));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function destroy(Request $request)
    {
        $scopes = $this->getSelectedRecords($request);

        foreach ($scopes as $scope) {
            try {
                $this->dispatch(new DeleteScope($scope));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
}
