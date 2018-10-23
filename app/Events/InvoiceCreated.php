<?php

namespace App\Events;

class InvoiceCreated
{
    public $invoice;

    /**
     * Create a new event instance.
     *
     * @param $invoice
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }
}
