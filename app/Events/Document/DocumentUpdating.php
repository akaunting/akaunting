<?php

namespace App\Events\Document;

use App\Abstracts\Event;

class DocumentUpdating extends Event
{
    public $document;

    public $request;

    /**
     * Create a new event instance.
     *
     * @param $document
     * @param $request
     */
    public function __construct($document, $request)
    {
        $this->document = $document;
        $this->request  = $request;
    }
}
