<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentCreated as Event;
use App\Traits\Documents;

class IncreaseNextDocumentNumber
{
    use Documents;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        // Update next document number
        $this->increaseNextDocumentNumber($event->document->type);
    }
}
