<?php

namespace App\Listeners\Auth;

use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Contracts\Session\Session;
use Illuminate\Auth\Events\Logout as Event;
use Illuminate\Support\Facades\Crypt;

class WorkhyAuthLogout
{

    public function __construct(protected Session $session)
    {}

    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $key = Config::get('workhy.auth.signed_key_name');

        if ($this->session->has($key)) {
            $token = Crypt::decryptString($this->session->get($key));

            PersonalAccessToken::findToken($token)?->delete();

            $this->session->forget($key);
        }
    }
}
