<?php

namespace App\BulkActions\Auth;

use App\Abstracts\BulkAction;
use App\Models\Auth\Role;

class Roles extends BulkAction
{
    public $model = Role::class;

    public $actions = [
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.delete',
            'permission' => 'delete-auth-roles',
        ],
    ];
}
