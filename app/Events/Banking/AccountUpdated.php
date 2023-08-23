<?php

namespace App\Events\Banking;

use App\Abstracts\Event;
use App\Models\Banking\Account;

class AccountUpdated extends Event
{
    public $account;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $account
     * @param $request
     */
    public function __construct(Account $account, $request)
    {
        $this->account = $account;
        $this->request  = $request;
    }
}
