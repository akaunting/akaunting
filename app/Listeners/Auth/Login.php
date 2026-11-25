<?php

namespace App\Listeners\Auth;

use App\Utilities\Date;
use Illuminate\Auth\Events\Login as Event;

class Login
{
    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        // Save user login time
        $event->user->last_logged_in_at = Date::now();

        $event->user->save();

        // The session may still hold the password of a user logged out on this browser,
        // which session.auth would take for a changed password, so the new one is written
        if (request()->hasSession()) {
            request()->session()->put('password_hash_' . $event->guard, $event->user->getAuthPassword());
        }
    }
}
