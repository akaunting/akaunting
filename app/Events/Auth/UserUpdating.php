<?php

namespace App\Events\Auth;

use App\Abstracts\Event;

class UserUpdating extends Event
{
    public $user;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $request
     */
    public function __construct($user, $request)
    {
        $this->user = $user;
        $this->request  = $request;
    }
}
