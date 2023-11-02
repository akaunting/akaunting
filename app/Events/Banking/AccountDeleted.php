<?php

namespace App\Events\Banking;

use App\Abstracts\Event;

class AccountDeleted extends Event
{
    public $account;

    /**
     * Create a new event instance.
     *
     * @param $account
     */
    public function __construct($account)
    {
        $this->account = $account;
    }
}
