<?php

namespace App\Events\Setting;

use App\Abstracts\Event;

class CurrencyUpdated extends Event
{
    public $currency;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $currency
     * @param $request
     */
    public function __construct($currency, $request)
    {
        $this->currency = $currency;
        $this->request = $request;
    }
}
