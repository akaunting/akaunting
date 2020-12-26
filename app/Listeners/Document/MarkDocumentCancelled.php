<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentCancelled as Event;
use App\Jobs\Document\CancelDocument;
use App\Jobs\Document\CreateDocumentHistory;
use App\Traits\Jobs;

class MarkDocumentCancelled
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
        $this->dispatch(new CancelDocument($event->document));

        $this->dispatch(new CreateDocumentHistory($event->document, 0, trans('general.messages.marked_cancelled', ['type' => ''])));
    }
}
