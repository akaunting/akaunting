<?php

namespace App\Events\Purchase;

use Illuminate\Queue\SerializesModels;

class BillCancelled
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
