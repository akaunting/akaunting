<?php

namespace App\Events\Auth;

use App\Abstracts\Event;

class InvitationCreated extends Event
{
    public $invitation;

    /**
     * Create a new event instance.
     *
     * @param $invitation
     */
    public function __construct($invitation)
    {
        $this->invitation = $invitation;
    }
}
