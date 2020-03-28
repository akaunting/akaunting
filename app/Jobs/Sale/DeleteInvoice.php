<?php

namespace App\Jobs\Sale;

use App\Abstracts\Job;
use App\Models\Sale\Invoice;
use App\Observers\Transaction;

class DeleteInvoice extends Job
{
    protected $invoice;

    /**
     * Create a new job instance.
     *
     * @param  $invoice
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
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

        $this->deleteRelationships($this->invoice, [
            'items', 'item_taxes', 'histories', 'transactions', 'recurring', 'totals'
        ]);

        $this->invoice->delete();

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
        if ($this->invoice->transactions()->isReconciled()->count()) {
            $message = trans('messages.warning.reconciled_doc', ['type' => trans_choice('general.invoices', 1)]);

            throw new \Exception($message);
        }
    }
}
