<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentRestored as Event;
use App\Jobs\Document\RestoreDocument as Job;
use App\Jobs\Document\CreateDocumentHistory;
use App\Traits\Jobs;

class RestoreDocument
{
    use Jobs;

    public function handle(Event $event): void
    {
        $this->dispatch(new Job($event->document));

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
                trans('documents.messages.restored', ['type' => $type])
            )
        );
    }
}
