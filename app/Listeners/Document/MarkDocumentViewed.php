<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentViewed as Event;
use App\Jobs\Document\CreateDocumentHistory;
use App\Traits\Jobs;
use Illuminate\Support\Str;

class MarkDocumentViewed
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
        $document = $event->document;

        if ($document->status != 'sent') {
            return;
        }

        unset($document->paid);

        $document->status = 'viewed';
        $document->save();

        $type = Str::plural($document->type);

        $this->dispatch(new CreateDocumentHistory($event->document, 0, trans("$type.messages.marked_viewed")));
    }
}
