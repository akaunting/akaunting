<?php

namespace App\Events\Setting;

use App\Abstracts\Event;

class CurrencyDeleted extends Event
{
    public $currency;

    /**
     * Create a new event instance.
     *
     * @param $currency
     */
    public function __construct($currency)
    {
        $this->currency = $currency;
    }
}
