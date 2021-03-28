<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;

class DeleteTransfer extends Job
{
    protected $transfer;

    /**
     * Create a new job instance.
     *
     * @param  $transfer
     */
    public function __construct($transfer)
    {
        $this->transfer = $transfer;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->transfer->expense_transaction->delete();
            $this->transfer->income_transaction->delete();
            $this->transfer->delete();
        });

        return true;
    }
}
