<?php

namespace App\Listeners\Email;

use App\Events\Email\InvalidEmailDetected as Event;

class DisablePersonDueToInvalidEmail
{
    public function handle(Event $event): void
    {
        $this->disableContact($event);

        $this->disableUser($event);
    }

    public function disableContact(Event $event): void
    {
        if (empty($event->contact)) {
            return;
        }

        $event->contact->enabled = false;
        $event->contact->save();
    }

    public function disableUser(Event $event): void
    {
        if (empty($event->user)) {
            return;
        }

        $event->user->enabled = false;
        $event->user->save();
    }
}
