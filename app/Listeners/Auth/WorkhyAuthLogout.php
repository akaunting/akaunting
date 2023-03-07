<?php

namespace App\Listeners\Auth;

use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Contracts\Session\Session;
use Illuminate\Auth\Events\Logout as Event;

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
            $encrypter = new \Illuminate\Encryption\Encrypter(
                Config::get('workhy.auth.signed_token_secret'),
                Config::get('workhy.auth.signed_token_cipher')
            );

            $token = $encrypter->decrypt($this->session->get($key));

            PersonalAccessToken::findToken($token)?->delete();

            $this->session->forget($key);
        }
    }
}
