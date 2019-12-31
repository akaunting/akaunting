<?php

namespace App\Events\Purchase;

use Illuminate\Queue\SerializesModels;

class BillUpdating
{
    use SerializesModels;

    public $bill;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $bill
     * @param $request
     */
    public function __construct($bill, $request)
    {
        $this->bill = $bill;
        $this->request = $request;
    }
}
