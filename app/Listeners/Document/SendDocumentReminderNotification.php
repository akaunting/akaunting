<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentReminded as Event;
use App\Traits\Documents;

class SendDocumentReminderNotification
{
    use Documents;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return array
     */
    public function handle(Event $event)
    {
        $document = $event->document;
        $notification = $event->notification;

        // Notify the customer
        if ($this->canNotifyTheContactOfDocument($document)) {
            $document->contact->notify(new $notification($document, "{$document->type}_remind_customer"));
        }

        // Notify all users assigned to this company
        foreach ($document->company->users as $user) {
            if ($user->cannot('read-notifications')) {
                continue;
            }

            $user->notify(new $notification($document, "{$document->type}_remind_admin"));
        }
    }
}
