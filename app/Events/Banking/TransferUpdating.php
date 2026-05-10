<?php

namespace App\Events\Banking;

use App\Abstracts\Event;
use App\Models\Banking\Transfer;

class TransferUpdating extends Event
{
    public $transfer;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $transfer
     * @param $request
     */
    public function __construct(Transfer $transfer, $request)
    {
        $this->transfer = $transfer;
        $this->request  = $request;
    }
}
