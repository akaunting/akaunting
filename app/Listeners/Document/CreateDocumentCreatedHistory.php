<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentCreated as Event;
use App\Jobs\Document\CreateDocumentHistory;
use App\Traits\Jobs;

class CreateDocumentCreatedHistory
{
    use Jobs;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $message = trans('messages.success.added', ['type' => $event->document->document_number]);

        $this->dispatch(new CreateDocumentHistory($event->document, 0, $message));
    }
}
