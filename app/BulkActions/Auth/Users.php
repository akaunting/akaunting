<?php

namespace App\BulkActions\Auth;

use App\Abstracts\BulkAction;
use App\Events\Auth\LandingPageShowing;
use App\Jobs\Auth\DeleteUser;
use App\Jobs\Auth\UpdateUser;

class Users extends BulkAction
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        $this->model = user_model_class();
    }

    public $text = 'general.users';

    public $path = [
        'group' => 'auth',
        'type' => 'users',
    ];

    public $actions = [
        'edit' => [
            'icon'          => 'edit',
            'name'          => 'general.edit',
            'message'       => '',
            'permission'    => 'update-auth-users',
            'type'          => 'modal',
            'handle'        => 'update',
        ],
        'enable'    => [
            'icon'          => 'check_circle',
            'name'          => 'general.enable',
            'message'       => 'bulk_actions.message.enable',
            'permission'    => 'update-auth-users',
        ],
        'disable'   => [
            'icon'          => 'hide_source',
            'name'          => 'general.disable',
            'message'       => 'bulk_actions.message.disable',
            'permission'    => 'update-auth-users',
        ],
        'delete'    => [
            'icon'          => 'delete',
            'name'          => 'general.delete',
            'message'       => 'bulk_actions.message.delete',
            'permission'    => 'delete-auth-users',
        ],
    ];

    public function edit($request)
    {
        $selected = $this->getSelectedInput($request);

        $roles = role_model_class()::all()->reject(function ($r) {
            $status = $r->hasPermission('read-client-portal');

            if ($r->name == 'employee') {
                $status = true;
            }

            return $status;
        })->pluck('display_name', 'id');

        $u = new \stdClass();
        $u->landing_pages = [];

        event(new LandingPageShowing($u));

        $landing_pages = $u->landing_pages;

        return $this->response('bulk-actions.auth.users.edit', compact('selected', 'roles', 'landing_pages'));
    }

    public function update($request)
    {
        $users = $this->getSelectedRecords($request, 'contact');

        foreach ($users as $user) {
            try {
                $request->merge([
                    'enabled' => $user->enabled,
                    'uploaded_logo' => $user->logo,
                ]); //

                $this->dispatch(new UpdateUser($user, $this->getUpdateRequest($request)));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function disable($request)
    {
        $users = $this->getSelectedRecords($request, 'contact');

        foreach ($users as $user) {
            try {
                $this->dispatch(new UpdateUser($user, $request->merge(['enabled' => 0])));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }

    public function destroy($request)
    {
        $users = $this->getSelectedRecords($request);

        foreach ($users as $user) {
            try {
                $this->dispatch(new DeleteUser($user));
            } catch (\Exception $e) {
                flash($e->getMessage())->error()->important();
            }
        }
    }
}
