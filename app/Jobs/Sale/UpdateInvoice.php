<?php

namespace App\Jobs\Sale;

use App\Abstracts\Job;
use App\Events\Sale\InvoiceUpdated;
use App\Events\Sale\InvoiceUpdating;
use App\Jobs\Sale\CreateInvoiceItemsAndTotals;
use App\Models\Sale\Invoice;
use App\Traits\Relationships;

class UpdateInvoice extends Job
{
    use Relationships;

    protected $invoice;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($invoice, $request)
    {
        $this->invoice = $invoice;
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

        event(new InvoiceUpdating($this->invoice, $this->request));

        \DB::transaction(function () {
            // Upload attachment
            if ($this->request->file('attachment')) {
                $media = $this->getMedia($this->request->file('attachment'), 'invoices');

                $this->invoice->attachMedia($media, 'attachment');
            }

            $this->deleteRelationships($this->invoice, ['items', 'item_taxes', 'totals']);

            $this->dispatch(new CreateInvoiceItemsAndTotals($this->invoice, $this->request));

            $invoice_paid = $this->invoice->paid;

            unset($this->invoice->reconciled);

            if (($invoice_paid) && $this->request['amount'] > $invoice_paid) {
                $this->request['status'] = 'partial';
            }

            $this->invoice->update($this->request->all());

            $this->invoice->updateRecurring();
        });

        event(new InvoiceUpdated($this->invoice, $this->request));

        return $this->invoice;
    }
}
