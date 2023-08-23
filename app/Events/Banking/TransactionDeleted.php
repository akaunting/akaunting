<?php

namespace App\Events\Banking;

use App\Abstracts\Event;

class TransactionDeleted extends Event
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
