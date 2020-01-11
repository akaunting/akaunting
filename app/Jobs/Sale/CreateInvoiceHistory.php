<?php

namespace App\Jobs\Sale;

use App\Abstracts\Job;
use App\Models\Sale\InvoiceHistory;

class CreateInvoiceHistory extends Job
{
    protected $invoice;

    protected $notify;

    protected $description;

    /**
     * Create a new job instance.
     *
     * @param  $invoice
     * @param  $notify
     * @param  $description
     */
    public function __construct($invoice, $notify = 0, $description = null)
    {
        $this->invoice = $invoice;
        $this->notify = $notify;
        $this->description = $description;
    }

    /**
     * Execute the job.
     *
     * @return InvoiceHistory
     */
    public function handle()
    {
        $description = $this->description ?: trans_choice('general.payments', 1);

        $invoice_history = InvoiceHistory::create([
            'company_id' => $this->invoice->company_id,
            'invoice_id' => $this->invoice->id,
            'status' => $this->invoice->status,
            'notify' => $this->notify,
            'description' => $description,
        ]);

        return $invoice_history;
    }
}
