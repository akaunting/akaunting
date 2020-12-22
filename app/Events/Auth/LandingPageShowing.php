<?php

namespace App\Events\Auth;

use Illuminate\Queue\SerializesModels;

class LandingPageShowing
{
    use SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }
}
