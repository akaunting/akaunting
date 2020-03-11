<?php

namespace App\Events\Banking;

use Illuminate\Queue\SerializesModels;

class TransactionCreated
{
    use SerializesModels;

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
