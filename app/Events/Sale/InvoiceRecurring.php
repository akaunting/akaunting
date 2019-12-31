<?php

namespace App\Events\Sale;

use Illuminate\Queue\SerializesModels;

class InvoiceRecurring
{
    use SerializesModels;

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
