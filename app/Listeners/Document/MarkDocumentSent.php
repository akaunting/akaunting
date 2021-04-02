<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentSent as Event;
use App\Jobs\Document\CreateDocumentHistory;
use App\Traits\Jobs;

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

        $type_text = '';

        if ($alias = config('type.' . $event->document->type . '.alias', '')) {
            $type_text .= $alias . '::';
        }

        $type_text .= 'general.' . config('type.' . $event->document->type .'.translation.prefix');

        $type = trans_choice($type_text, 1);

        $this->dispatch(
            new CreateDocumentHistory(
                $event->document,
                0,
                trans('documents.messages.marked_sent', ['type' => $type])
            )
        );
    }
}
