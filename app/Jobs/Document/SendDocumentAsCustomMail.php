<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Events\Document\DocumentSent;
use App\Models\Document\Document;

class SendDocumentAsCustomMail extends Job
{
    public function __construct($request, $template_alias)
    {
        $this->request = $request;
        $this->template_alias = $template_alias;
    }

    public function handle(): void
    {
        $document = Document::find($this->request->get('document_id'));

        $custom_mail = $this->request->only(['to', 'subject', 'body']);

        if ($this->request->get('user_email', false)) {
            $custom_mail['cc'] = user()->email;
        }

        $notification = config('type.document.' . $document->type . '.notification.class');

        // Notify the contact
        $document->contact->notify(new $notification($document, $this->template_alias, true, $custom_mail));

        event(new DocumentSent($document));
    }
}
