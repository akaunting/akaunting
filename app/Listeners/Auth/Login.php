<?php

namespace App\Listeners\Auth;

use Date;
use Illuminate\Auth\Events\Login as ILogin;

class Login
{

    /**
     * Handle the event.
     *
     * @param ILogin $event
     * @return void
     */
    public function handle(ILogin $event)
    {
        // Set company id
        $company = $event->user->companies()->first();

        session(['company_id' => $company->id]);

        // Save user login time
        $event->user->last_logged_in_at = Date::now();

        $event->user->save();
    }
}
