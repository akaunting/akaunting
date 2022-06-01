<?php

namespace App\Listeners\Auth;

use App\Events\Auth\InvitationCreated as Event;
use App\Notifications\Auth\Invitation as Notification;

class SendUserInvitation
{
    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $invitation = $event->invitation;

        $invitation->user->notify(new Notification($invitation));
    }
}
