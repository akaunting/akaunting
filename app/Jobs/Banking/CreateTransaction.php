<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Banking\TransactionCreated;
use App\Events\Banking\TransactionCreating;
use App\Models\Banking\Transaction;

class CreateTransaction extends Job
{
    protected $transaction;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
        $this->request->merge(['created_by' => user_id()]);
    }

    /**
     * Execute the job.
     *
     * @return Transaction
     */
    public function handle()
    {
        event(new TransactionCreating($this->request));

        \DB::transaction(function () {
            $this->transaction = Transaction::create($this->request->all());

            // Upload attachment
            if ($this->request->file('attachment')) {
                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, 'transactions');

                    $this->transaction->attachMedia($media, 'attachment');
                }
            }

            // Recurring
            $this->transaction->createRecurring($this->request->all());
        });

        event(new TransactionCreated($this->transaction));

        return $this->transaction;
    }
}
