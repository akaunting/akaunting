<?php

namespace App\Events\Auth;

use App\Abstracts\Event;

class ApiPermissionsAssigning extends Event
{
    public $permission;

    public $table;

    public $type;

    /**
     * Create a new event instance.
     *
     * @param $permission
     * @param $table
     * @param $type
     */
    public function __construct($permission, $table, $type)
    {
        $this->permission = $permission;
        $this->table = $table;
        $this->type = $type;
    }
}
