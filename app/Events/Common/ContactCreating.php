<?php

namespace App\Events\Common;

use App\Abstracts\Event;

class ContactCreating extends Event
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
