<?php

namespace App\Jobs\Expense;

use App\Abstracts\Job;
use App\Models\Expense\Bill;
use App\Models\Expense\BillHistory;

class DuplicateBill extends Job
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
        $clone = $this->bill->duplicate();

        // Add bill history
        BillHistory::create([
            'company_id' => session('company_id'),
            'bill_id' => $clone->id,
            'status_code' => 'draft',
            'notify' => 0,
            'description' => trans('messages.success.added', ['type' => $clone->bill_number]),
        ]);

        return $clone;
    }
}
