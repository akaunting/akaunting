<?php

namespace App\Events\Email;

use App\Abstracts\Event;

class TooManyEmailsSent extends Event
{
    public $user_id;

    public function __construct(int $user_id)
    {
        $this->user_id = $user_id;
    }
}
