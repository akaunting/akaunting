<?php

namespace App\Jobs\Sale;

use App\Abstracts\Job;
use App\Events\Sale\InvoiceCreated;
use App\Models\Sale\Invoice;

class DuplicateInvoice extends Job
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
        $clone = $this->invoice->duplicate();

        event(new InvoiceCreated($clone));

        return $clone;
    }
}
