<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Banking\TransactionSending;
use App\Events\Banking\TransactionSent;
use App\Models\Banking\Transaction;
use App\Notifications\Banking\Transaction as Notification;

class SendTransaction extends Job
{
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function handle(): void
    {
        event(new TransactionSending($this->transaction));

        // Notify the customer/vendor
        $this->transaction->contact->notify(new Notification($this->transaction, config('type.transaction.' . $this->transaction->type . '.email_template'), true));

        event(new TransactionSent($this->transaction));
    }
}
