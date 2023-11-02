<?php

namespace App\Events\Banking;

use App\Abstracts\Event;
use App\Models\Banking\Account;

class AccountCreated extends Event
{
    public $account;

    /**
     * Create a new event instance.
     *
     * @param $account
     */
    public function __construct(Account $account)
    {
        $this->account = $account;
    }
}
