<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Models\Banking\Transaction;

class UpdateTransaction extends Job
{
    protected $transaction;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $transaction
     * @param  $request
     */
    public function __construct($transaction, $request)
    {
        $this->transaction = $transaction;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Transaction
     */
    public function handle()
    {
        $this->transaction->update($this->request->all());

        // Upload attachment
        if ($this->request->file('attachment')) {
            $media = $this->getMedia($this->request->file('attachment'), 'transactions');

            $this->transaction->attachMedia($media, 'attachment');
        }

        // Recurring
        $this->transaction->updateRecurring();

        return $this->transaction;
    }
}
