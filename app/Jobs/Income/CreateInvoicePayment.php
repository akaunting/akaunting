<?php

namespace App\Jobs\Income;

use App\Models\Income\InvoiceHistory;
use App\Models\Income\InvoicePayment;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateInvoicePayment
{
    use Dispatchable;

    protected $request;

    protected $invoice;

    /**
     * Create a new job instance.
     *
     * @param  $request
     * @param  $invoice
     */
    public function __construct($request, $invoice)
    {
        $this->request = $request;
        $this->invoice = $invoice;
    }

    /**
     * Execute the job.
     *
     * @return InvoicePayment
     */
    public function handle()
    {
        $invoice_payment = InvoicePayment::create($this->request->input());

        $desc_amount = money((float) $invoice_payment->amount, (string) $invoice_payment->currency_code, true)->format();

        $history_data = [
            'company_id' => $invoice_payment->company_id,
            'invoice_id' => $invoice_payment->invoice_id,
            'status_code' => $this->invoice->invoice_status_code,
            'notify' => '0',
            'description' => $desc_amount . ' ' . trans_choice('general.payments', 1),
        ];

        InvoiceHistory::create($history_data);

        return $invoice_payment;
    }
}