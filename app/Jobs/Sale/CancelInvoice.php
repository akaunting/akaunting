<?php

namespace App\Jobs\Sale;

use App\Abstracts\Job;
use App\Models\Sale\Invoice;

class CancelInvoice extends Job
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
            'transactions', 'recurring'
        ]);

        $this->invoice->status = 'cancelled';
        $this->invoice->save();

        return $this->invoice;
    }
}
