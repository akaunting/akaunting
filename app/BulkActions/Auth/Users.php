<?php

namespace App\BulkActions\Auth;

use App\Abstracts\BulkAction;
use App\Jobs\Auth\DeleteUser;
use App\Jobs\Auth\UpdateUser;
use App\Models\Auth\User;

class Users extends BulkAction
{
    public $model = User::class;

    public $text = 'general.users';

    public $path = [
        'group' => 'auth',
        'type' => 'users',
    ];

    public $actions = [
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
