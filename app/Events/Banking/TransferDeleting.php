<?php

namespace App\Events\Banking;

use App\Abstracts\Event;

class TransferDeleting extends Event
{
    public $transfer;

    /**
     * Create a new event instance.
     *
     * @param $transfer
     */
    public function __construct($transfer)
    {
        $this->transfer = $transfer;
    }
}
