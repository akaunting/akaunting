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
        $this->authorize();

        \DB::transaction(function () {
            $this->transaction->update($this->request->all());

            // Upload attachment
            if ($this->request->file('attachment')) {
                $this->deleteMediaModel($this->transaction, 'attachment', $this->request);

                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, 'transactions');

                    $this->transaction->attachMedia($media, 'attachment');
                }
            } elseif (!$this->request->file('attachment') && $this->transaction->attachment) {
                $this->deleteMediaModel($this->transaction, 'attachment', $this->request);
            }

            // Recurring
            $this->transaction->updateRecurring($this->request->all());
        });

        return $this->transaction;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if ($this->transaction->reconciled) {
            $message = trans('messages.warning.reconciled_tran');

            throw new \Exception($message);
        }
    }
}
