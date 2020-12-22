<?php

namespace App\Events\Purchase;

use Illuminate\Queue\SerializesModels;

class BillReceived
{
    use SerializesModels;

    public $bill;

    /**
     * Create a new event instance.
     *
     * @param $bill
     */
    public function __construct($bill)
    {
        $this->bill = $bill;
    }
}
