<?php

namespace App\Events\Banking;

use App\Abstracts\Event;

class TransactionDuplicating extends Event
{
    public $transaction;

    /**
     * Create a new event instance.
     *
     * @param $transaction
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }
}
