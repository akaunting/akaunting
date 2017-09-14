<?php

namespace App\Listeners\Auth;

use Auth;
use Illuminate\Auth\Events\Login as ILogin;

class Login
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param Logout $event
     * @return void
     */
    public function handle(ILogin $event)
    {
        // Get company
        $company = Auth::user()->companies()->first();

        session(['company_id' => $company->id]);
    }
}
