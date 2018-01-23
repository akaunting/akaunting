<?php

namespace App\Listeners\Auth;

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
        session()->forget('company_id');
    }
}