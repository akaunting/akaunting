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

        // If only one user is left, don't disable it
        $users = company()?->users;

        if ($users && $users->count() <= 1) {
            return;
        }

        $companies = $event->user->companies;

        foreach ($companies as $company) {
            if ($company->users->count() <= 1) {
                return;
            }
        }

        $event->user->enabled = false;
        $event->user->save();
    }
}
