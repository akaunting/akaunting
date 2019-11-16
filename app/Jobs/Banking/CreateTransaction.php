<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Models\Banking\Transaction;

class CreateTransaction extends Job
{
    protected $request;

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
        $transaction = Transaction::create($this->request->all());

        // Upload attachment
        if ($this->request->file('attachment')) {
            $media = $this->getMedia($this->request->file('attachment'), 'transactions');

            $transaction->attachMedia($media, 'attachment');
        }

        // Recurring
        $transaction->createRecurring();

        return $transaction;
    }
}
