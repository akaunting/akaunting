<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentViewed as Event;
use App\Jobs\Document\CreateDocumentHistory;
use App\Traits\Jobs;

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

        $type = trans_choice(
            config("type.{$event->document->type}.alias", '') .
            'general.' . config("type.{$event->document->type}.translation_key"),
            1
        );

        $this->dispatch(
            new CreateDocumentHistory(
                $event->document,
                0,
                trans('documents.messages.marked_viewed', ['type' => $type])
            )
        );
    }
}
