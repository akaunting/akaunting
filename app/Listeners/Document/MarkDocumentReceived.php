<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentReceived as Event;
use App\Jobs\Document\CreateDocumentHistory;
use App\Traits\Jobs;

class MarkDocumentReceived
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
            $event->document->status = 'received';

            $event->document->save();
        }

        $type = trans_choice(
            config("type.{$event->document->type}.alias", '') .
            'general.' . config("type.{$event->document->type}.translation_key"),
            1
        );

        $this->dispatch(
            new CreateDocumentHistory(
                $event->document,
                0,
                trans('documents.messages.marked_received', ['type' => $type])
            )
        );
    }
}
