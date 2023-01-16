<?php

namespace App\Events\Auth;

use App\Abstracts\Event;

class RoleDeleting extends Event
{
    public $role;

    /**
     * Create a new event instance.
     *
     * @param $role
     */
    public function __construct($role)
    {
        $this->role = $role;
    }
}
