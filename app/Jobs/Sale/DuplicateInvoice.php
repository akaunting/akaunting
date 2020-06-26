<?php

namespace App\Jobs\Sale;

use App\Abstracts\Job;
use App\Events\Sale\InvoiceCreated;
use App\Models\Sale\Invoice;

class DuplicateInvoice extends Job
{
    protected $invoice;

    protected $clone;

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
        \DB::transaction(function () {
            $this->clone = $this->invoice->duplicate();
        });

        event(new InvoiceCreated($this->clone));

        return $this->clone;
    }
}
