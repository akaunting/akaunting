<?php

namespace App\Events\Expense;

use Illuminate\Queue\SerializesModels;

class BillCreating
{
    use SerializesModels;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $request
     */
    public function __construct($request)
    {
        $this->request = $request;
    }
}
