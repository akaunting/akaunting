<?php

namespace App\Jobs\Expense;

use App\Abstracts\Job;
use App\Models\Expense\Bill;

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
        $this->deleteRelationships($this->bill, [
            'items', 'item_taxes', 'histories', 'transactions', 'recurring', 'totals'
        ]);

        $this->bill->delete();

        return true;
    }
}
