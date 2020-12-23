<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentSent as Event;
use App\Jobs\Document\CreateDocumentHistory;
use App\Traits\Jobs;
use Illuminate\Support\Str;

class MarkDocumentSent
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
        if ($event->document->status != 'partial') {
            $event->document->status = 'sent';

            $event->document->save();
        }

        $type = Str::plural($event->document->type);

        $this->dispatch(new CreateDocumentHistory($event->document, 0, trans("$type.messages.marked_sent")));
    }
}
