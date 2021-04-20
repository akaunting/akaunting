<?php

namespace App\Events\Document;

use App\Abstracts\Event;
use App\Models\Document\Document;

class DocumentRecurring extends Event
{
    public $document;

    /**
     * Create a new event instance.
     *
     * @param $document
     */
    public function __construct(Document $document)
    {
        $this->document     = $document;
    }
}
