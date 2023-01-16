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
    }
}
