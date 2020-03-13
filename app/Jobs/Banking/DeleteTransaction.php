<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Models\Setting\Category;

class DeleteTransaction extends Job
{
    protected $transaction;

    /**
     * Create a new job instance.
     *
     * @param  $transaction
     */
    public function __construct($transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        $this->authorize();

        $this->transaction->recurring()->delete();
        $this->transaction->delete();

        return true;
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

        if ($this->transaction->category->id == Category::transfer()) {
            throw new \Exception('Unauthorized');
        }
    }
}
