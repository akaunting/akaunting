<?php

namespace Laravel\Sanctum\Events;

class TokenAuthenticated
{
    /**
     * The personal access token that was authenticated.
     *
     * @var \Laravel\Sanctum\PersonalAccessToken
     */
    public $token;

    /**
     * Create a new event instance.
     *
     * @param  \Laravel\Sanctum\PersonalAccessToken  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }
}
