<?php

namespace App\Events\Banking;

use App\Abstracts\Event;
use App\Models\Banking\Transaction;

class TransactionUpdated extends Event
{
    public $transaction;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $transaction
     * @param $request
     */
    public function __construct(Transaction $transaction, $request)
    {
        $this->transaction = $transaction;
        $this->request  = $request;
    }
}
