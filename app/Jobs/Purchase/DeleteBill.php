<?php

namespace App\Jobs\Purchase;

use App\Abstracts\Job;
use App\Models\Purchase\Bill;
use App\Observers\Transaction;

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
     * @return boolean|Exception
     */
    public function handle()
    {
        $this->authorize();

        Transaction::mute();

        $this->deleteRelationships($this->bill, [
            'items', 'item_taxes', 'histories', 'transactions', 'recurring', 'totals'
        ]);

        $this->bill->delete();

        Transaction::unmute();

        return true;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if ($this->bill->transactions()->isReconciled()->count()) {
            $message = trans('messages.warning.reconciled_doc', ['type' => trans_choice('general.bills', 1)]);

            throw new \Exception($message);
        }
    }
}
