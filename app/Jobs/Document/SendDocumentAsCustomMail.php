<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Events\Document\DocumentSending;
use App\Events\Document\DocumentSent;
use App\Http\Requests\Common\CustomMail as Request;
use App\Models\Document\Document;

class SendDocumentAsCustomMail extends Job
{
    public string $template_alias;

    public function __construct(Request $request, string $template_alias)
    {
        $this->request = $request;
        $this->template_alias = $template_alias;
    }

    public function handle(): void
    {
        $document = Document::find($this->request->get('document_id'));

        event(new DocumentSending($document));

        $custom_mail = $this->request->only(['to', 'subject', 'body']);

        if ($this->request->get('user_email', false)) {
            $custom_mail['cc'] = user()->email;
        }

        $attachments = collect($this->request->get('attachments', []))
            ->filter(fn($value) => $value == true)
            ->keys()
            ->all();

        $notification = config('type.document.' . $document->type . '.notification.class');

        // Notify the contact
        $document->contact->notify(new $notification($document, $this->template_alias, true, $custom_mail, $attachments));

        event(new DocumentSent($document));
    }
}
