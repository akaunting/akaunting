<?php

namespace App\Events\Expense;

use Illuminate\Queue\SerializesModels;

class BillCreated
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
