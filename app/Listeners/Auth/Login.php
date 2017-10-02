<?php

namespace App\Listeners\Auth;

use Auth;
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
        // Get company
        $company = Auth::user()->companies()->first();

        session(['company_id' => $company->id]);
    }
}
