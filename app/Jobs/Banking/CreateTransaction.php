<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Banking\TransactionCreated;
use App\Events\Banking\TransactionCreating;
use App\Models\Banking\Transaction;

class CreateTransaction extends Job
{
    protected $request;

    protected $transaction;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Transaction
     */
    public function handle()
    {
        event(new TransactionCreating($this->request));

        $this->transaction = Transaction::create($this->request->all());

        // Upload attachment
        if ($this->request->file('attachment')) {
            $media = $this->getMedia($this->request->file('attachment'), 'transactions');

            $this->transaction->attachMedia($media, 'attachment');
        }

        // Recurring
        $this->transaction->createRecurring();

        event(new TransactionCreated($this->transaction));

        return $this->transaction;
    }
}
