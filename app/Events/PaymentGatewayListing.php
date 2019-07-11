<?php

namespace App\Events;

class PaymentGatewayListing
{
    public $gateways;

    /**
     * Create a new event instance.
     *
     * @param $gateways
     */
    public function __construct($gateways)
    {
        $this->gateways = $gateways;
    }
}
