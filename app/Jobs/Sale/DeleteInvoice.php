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
     * @return Invoice
     */
    public function handle()
    {
        Transaction::mute();

        $this->deleteRelationships($this->invoice, [
            'items', 'item_taxes', 'histories', 'transactions', 'recurring', 'totals'
        ]);

        $this->invoice->delete();

        Transaction::unmute();

        return true;
    }
}
