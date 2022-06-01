<?php

namespace App\Events\Banking;

use App\Abstracts\Event;
use App\Models\Banking\Transaction;

class TransactionCreated extends Event
{
    public $transaction;

    /**
     * Create a new event instance.
     *
     * @param $transaction
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }
}
