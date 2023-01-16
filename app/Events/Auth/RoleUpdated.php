<?php

namespace App\Events\Auth;

use App\Abstracts\Event;

class RoleUpdated extends Event
{
    public $role;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $role
     * @param $request
     */
    public function __construct($role, $request)
    {
        $this->role = $role;
        $this->request  = $request;
    }
}
