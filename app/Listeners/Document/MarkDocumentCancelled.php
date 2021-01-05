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

        $type = trans_choice(
            config("type.{$event->document->type}.alias", '') .
            'general.' . config("type.{$event->document->type}.translation_key"),
            1
        );

        $this->dispatch(
            new CreateDocumentHistory(
                $event->document,
                0,
                trans('documents.messages.marked_cancelled', ['type' => $type])
            )
        );
    }
}
