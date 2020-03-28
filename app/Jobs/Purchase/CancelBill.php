<?php

namespace App\Jobs\Purchase;

use App\Abstracts\Job;
use App\Models\Purchase\Bill;

class CancelBill extends Job
{
    protected $bill;

    /**
     * Create a new job instance.
     *
     * @param  $bill
     */
    public function __construct($bill)
    {
        $this->bill = $bill;
    }

    /**
     * Execute the job.
     *
     * @return Bill
     */
    public function handle()
    {
        $this->deleteRelationships($this->bill, [
            'transactions', 'recurring'
        ]);

        $this->bill->status = 'cancelled';
        $this->bill->save();

        return $this->bill;
    }
}
