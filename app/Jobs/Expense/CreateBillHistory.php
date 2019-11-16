<?php

namespace App\Jobs\Expense;

use App\Abstracts\Job;
use App\Models\Expense\BillHistory;

class CreateBillHistory extends Job
{
    protected $bill;

    protected $notify;

    protected $description;

    /**
     * Create a new job instance.
     *
     * @param  $bill
     * @param  $notify
     * @param  $description
     */
    public function __construct($bill, $notify = 0, $description = null)
    {
        $this->bill = $bill;
        $this->notify = $notify;
        $this->description = $description;
    }

    /**
     * Execute the job.
     *
     * @return BillHistory
     */
    public function handle()
    {
        $description = $this->description ?: trans_choice('general.payments', 1);

        $bill_history = BillHistory::create([
            'company_id' => $this->bill->company_id,
            'bill_id' => $this->bill->id,
            'status_code' => $this->bill->bill_status_code,
            'notify' => $this->notify,
            'description' => $description,
        ]);

        return $bill_history;
    }
}
