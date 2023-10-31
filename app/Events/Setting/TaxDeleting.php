<?php

namespace App\Events\Setting;

use App\Abstracts\Event;

class TaxDeleting extends Event
{
    public $tax;

    /**
     * Create a new event instance.
     *
     * @param $tax
     */
    public function __construct($tax)
    {
        $this->tax = $tax;
    }
}
