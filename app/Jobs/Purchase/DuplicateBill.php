<?php

namespace App\Jobs\Purchase;

use App\Abstracts\Job;
use App\Events\Purchase\BillCreated;
use App\Models\Purchase\Bill;

class DuplicateBill extends Job
{
    protected $bill;

    protected $clone;

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
        \DB::transaction(function () {
            $this->clone = $this->bill->duplicate();
        });

        event(new BillCreated($this->clone));

        return $this->clone;
    }
}
