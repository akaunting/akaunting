<?php

namespace App\Events\Banking;

use App\Abstracts\Event;

class TransferCreating extends Event
{
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
