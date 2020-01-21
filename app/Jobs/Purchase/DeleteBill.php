<?php

namespace App\Jobs\Purchase;

use App\Abstracts\Job;
use App\Models\Purchase\Bill;

class DeleteBill extends Job
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
        session(['deleting_bill' => true]);

        $this->deleteRelationships($this->bill, [
            'items', 'item_taxes', 'histories', 'transactions', 'recurring', 'totals'
        ]);

        $this->bill->delete();

        session()->forget('deleting_bill');

        return true;
    }
}
