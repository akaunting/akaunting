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
        if (! in_array($event->document->status, ['partial', 'paid'])) {
            $event->document->status = 'received';

            //This control will be removed when approval status is added to documents.
            if ($event->document->amount == 0) {
                $event->document->status = 'paid';
            }

            $event->document->save();
        }

        $type_text = '';

        if ($alias = config('type.document.' . $event->document->type . '.alias', '')) {
            $type_text .= $alias . '::';
        }

        $type_text .= 'general.' . config('type.document.' . $event->document->type .'.translation.prefix');

        $type = trans_choice($type_text, 1);

        $this->dispatch(
            new CreateDocumentHistory(
                $event->document,
                0,
                trans('documents.messages.marked_received', ['type' => $type])
            )
        );
    }
}
