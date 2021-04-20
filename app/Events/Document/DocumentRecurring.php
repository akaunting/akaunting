<?php

namespace App\Events\Document;

use App\Abstracts\Event;
use App\Models\Document\Document;

class DocumentRecurring extends Event
{
    public $document;
    public $notification;

    /**
     * Create a new event instance.
     */
    public function __construct(Document $document, string $notification)
    {
        $this->document     = $document;
        $this->notification = $notification;
    }
}
