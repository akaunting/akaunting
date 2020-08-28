<?php

namespace App\Jobs\Sale;

use App\Abstracts\Job;
use App\Events\Sale\InvoiceCreated;
use App\Events\Sale\InvoiceCreating;
use App\Jobs\Sale\CreateInvoiceItemsAndTotals;
use App\Models\Sale\Invoice;

class CreateInvoice extends Job
{
    protected $invoice;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Invoice
     */
    public function handle()
    {
        if (empty($this->request['amount'])) {
            $this->request['amount'] = 0;
        }

        event(new InvoiceCreating($this->request));

        \DB::transaction(function () {
            $this->invoice = Invoice::create($this->request->all());

            // Upload attachment
            if ($this->request->file('attachment')) {
                $media = $this->getMedia($this->request->file('attachment'), 'invoices');

                $this->invoice->attachMedia($media, 'attachment');
            }

            $this->dispatch(new CreateInvoiceItemsAndTotals($this->invoice, $this->request));

            $this->invoice->update($this->request->all());

            $this->invoice->createRecurring();
        });

        event(new InvoiceCreated($this->invoice));

        return $this->invoice;
    }
}
