<?php

namespace App\Events\Document;

use App\Abstracts\Event;

class PaymentReceived extends Event
{
    public $document;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $document
     */
    public function __construct($document, $request = [])
    {
        $this->document = $document;
        $this->request  = $request;
    }
}
