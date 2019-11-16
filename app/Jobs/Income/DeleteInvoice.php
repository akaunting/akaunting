<?php

namespace App\Jobs\Income;

use App\Abstracts\Job;
use App\Models\Income\Invoice;

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
        $this->deleteRelationships($this->invoice, [
            'items', 'item_taxes', 'histories', 'transactions', 'recurring', 'totals'
        ]);

        $this->invoice->delete();

        return true;
    }
}
