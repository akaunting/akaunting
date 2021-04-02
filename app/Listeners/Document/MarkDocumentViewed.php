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
                trans('documents.messages.marked_viewed', ['type' => $type])
            )
        );
    }
}
