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
        // Get first company
        $company = $event->user->companies()->enabled()->first();
        
        // Logout if no company assigned
        if (!$company) {
            app('App\Http\Controllers\Auth\Login')->logout();
            
            flash(trans('auth.error.no_company'))->error();
            
            return;
        }

        // Set company id
        session(['company_id' => $company->id]);

        // Save user login time
        $event->user->last_logged_in_at = Date::now();

        $event->user->save();
    }
}
