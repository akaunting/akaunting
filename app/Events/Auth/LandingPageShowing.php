<?php

namespace App\Events\Auth;

use App\Abstracts\Event;

class LandingPageShowing extends Event
{
    public $user;

    /**
     * Create a new event instance.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
