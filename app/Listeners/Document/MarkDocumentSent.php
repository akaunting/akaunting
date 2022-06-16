<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentMarkedSent;
use App\Events\Document\DocumentSent;
use App\Jobs\Document\CreateDocumentHistory;
use App\Traits\Jobs;

class MarkDocumentSent
{
    use Jobs;

    public function handle(DocumentMarkedSent|DocumentSent $event): void
    {
        if ($event->document->status != 'partial') {
            $event->document->status = 'sent';

            $event->document->save();
        }

        $this->dispatch(new CreateDocumentHistory($event->document, 0, $this->getDescription($event)));
    }

    public function getDescription(DocumentMarkedSent|DocumentSent $event): string
    {
        $type_text = '';

        if ($alias = config('type.document.' . $event->document->type . '.alias', '')) {
            $type_text .= $alias . '::';
        }

        $type_text .= 'general.' . config('type.document.' . $event->document->type .'.translation.prefix');

        $type = trans_choice($type_text, 1);

        $message = ($event instanceof DocumentMarkedSent) ? 'marked_sent' : 'email_sent';

        return trans('documents.messages.' . $message, ['type' => $type]);
    }
}
