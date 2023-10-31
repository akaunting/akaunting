<?php

namespace App\Events\Setting;

use App\Abstracts\Event;

class TaxUpdating extends Event
{
    public $tax;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $tax
     * @param $request
     */
    public function __construct($tax, $request)
    {
        $this->tax = $tax;
        $this->request = $request;
    }
}
