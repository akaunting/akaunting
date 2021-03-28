<?php

namespace App\Listeners\Auth;

use Illuminate\Auth\Events\Logout as Event;

class Logout
{
    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        session()->forget('company_id');
    }
}
