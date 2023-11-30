<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Events\Document\DocumentSending;
use App\Events\Document\DocumentSent;
use App\Models\Document\Document;

class SendDocument extends Job
{
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function handle(): void
    {
        event(new DocumentSending($this->document));

        $notification = config('type.document.' . $this->document->type . '.notification.class');

        // Notify the customer
        $this->document->contact->notify(new $notification($this->document, 'invoice_new_customer', true));

        event(new DocumentSent($this->document));
    }
}
