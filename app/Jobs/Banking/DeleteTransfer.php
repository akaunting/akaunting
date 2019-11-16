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
     * @return mixed
     */
    public function handle()
    {
        $this->deleteRelationships($this->transfer, ['expense_transaction', 'income_transaction']);

        $this->transfer->delete();

        return true;
    }
}
