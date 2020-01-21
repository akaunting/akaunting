<?php

namespace App\Jobs\Sale;

use App\Abstracts\Job;
use App\Models\Sale\Invoice;

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
     * @return Invoice
     */
    public function handle()
    {
        session(['deleting_invoice' => true]);

        $this->deleteRelationships($this->invoice, [
            'items', 'item_taxes', 'histories', 'transactions', 'recurring', 'totals'
        ]);

        $this->invoice->delete();

        session()->forget('deleting_invoice');

        return true;
    }
}
