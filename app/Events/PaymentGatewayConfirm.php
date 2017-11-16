<?php

namespace App\Events;

class PaymentGatewayConfirm
{
    public $gateway;

    public $invoice;

    /**
     * Create a new event instance.
     *
     * @param $gateway
     * @param $invoice
     */
    public function __construct($gateway, $invoice)
    {
        $this->gateway = $gateway;
        $this->invoice = $invoice;
    }
}
