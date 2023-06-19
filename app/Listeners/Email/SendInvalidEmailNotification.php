<?php

namespace App\Listeners\Email;

use App\Events\Email\InvalidEmailDetected as Event;
use App\Notifications\Email\InvalidEmail;
use Illuminate\Support\Str;

class SendInvalidEmailNotification
{
    public function handle(Event $event): void
    {
        $users = company()?->users;

        if (empty($users)) {
            return;
        }

        $this->notifyAdminsAboutInvalidContactEmail($event, $users);

        $this->notifyAdminsAboutInvalidUserEmail($event, $users);
    }

    public function notifyAdminsAboutInvalidContactEmail(Event $event, $users): void
    {
        if (empty($event->contact)) {
            return;
        }

        if ($event->contact->isCustomer() || $event->contact->isVendor() || $event->contact->isEmployee()) {
            $type = trans_choice('general.' . Str::plural($event->contact->type), 1);
        } else {
            $type = ucfirst($event->contact->type);
        }

        foreach ($users as $user) {
            if ($user->cannot('read-notifications')) {
                continue;
            }

            $user->notify(new InvalidEmail($event->email, $type, $event->error));
        }
    }

    public function notifyAdminsAboutInvalidUserEmail(Event $event, $users): void
    {
        if (empty($event->user)) {
            return;
        }

        $type = trans_choice('general.users', 1);

        foreach ($users as $user) {
            if ($user->cannot('read-notifications')) {
                continue;
            }

            if ($user->email == $event->email) {
                continue;
            }

            $user->notify(new InvalidEmail($event->email, $type, $event->error));
        }
    }
}
