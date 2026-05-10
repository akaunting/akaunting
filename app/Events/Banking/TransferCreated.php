<?php

namespace App\Events\Banking;

use App\Abstracts\Event;
use App\Models\Banking\Transfer;

class TransferCreated extends Event
{
    public $transfer;

    /**
     * Create a new event instance.
     *
     * @param $transfer
     */
    public function __construct(Transfer $transfer)
    {
        $this->transfer = $transfer;
    }
}
