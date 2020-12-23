<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentCancelled as Event;
use App\Jobs\Document\CancelDocument;
use App\Jobs\Document\CreateDocumentHistory;
use App\Traits\Jobs;
use Illuminate\Support\Str;

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

        $type = Str::plural($event->document->type);
        $this->dispatch(new CreateDocumentHistory($event->document, 0, trans("$type.messages.marked_cancelled")));
    }
}
