<?php

namespace App\Events;

class InvoicePaid
{
    public $invoice;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $invoice
     */
    public function __construct($invoice, $request)
    {
        $this->invoice = $invoice;
        $this->request = $request;
    }
}
