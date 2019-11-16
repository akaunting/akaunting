<?php

namespace App\BulkActions\Auth;

use App\Abstracts\BulkAction;
use App\Models\Auth\Permission;

class Permissions extends BulkAction
{

    public $model = Permission::class;

    public $actions = [
        'delete' => [
            'name' => 'general.delete',
            'message' => 'bulk_actions.message.deletes',
            'permission' => 'delete-auth-permissions'
        ]
    ];
}
