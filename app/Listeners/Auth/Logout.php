<?php

namespace App\Listeners\Auth;

use Jenssegers\Date\Date;
use Illuminate\Auth\Events\Logout as ILogout;

class Logout
{

    /**
     * Handle the event.
     *
     * @param ILogout $event
     * @return void
     */
    public function handle(ILogout $event)
    {
        if (empty($event->user)) {
            return;
        }
        
        $event->user->last_logged_in_at = Date::now();

        $event->user->save();

        session()->forget('company_id');
    }
}