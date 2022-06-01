<?php

namespace App\Listeners\Auth;

use App\Events\Auth\UserDeleted as Event;
use App\Jobs\Auth\DeleteInvitation;
use App\Models\Auth\UserInvitation;
use App\Traits\Jobs;

class DeleteUserInvitation
{
    use Jobs;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $invitations = UserInvitation::where('user_id', $event->user->id)->get();

        foreach ($invitations as $invitation) {
            $this->dispatch(new DeleteInvitation($invitation));
        }
    }
}
