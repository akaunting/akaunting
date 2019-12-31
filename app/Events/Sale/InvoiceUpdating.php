<?php

namespace App\Events\Sale;

use Illuminate\Queue\SerializesModels;

class InvoiceUpdating
{
    use SerializesModels;

    public $invoice;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $invoice
     * @param $request
     */
    public function __construct($invoice, $request)
    {
        $this->invoice = $invoice;
        $this->request = $request;
    }
}
