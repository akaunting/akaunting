<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentReminded as Event;

class SendDocumentReminderNotification
{
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
        if ($document->contact && !empty($document->contact_email)) {
            $document->contact->notify(new $notification($document, "{$document->type}_remind_customer"));
        }

        // Notify all users assigned to this company
        foreach ($document->company->users as $user) {
            if (!$user->can('read-notifications')) {
                continue;
            }

            $user->notify(new $notification($document, "{$document->type}_remind_admin"));
        }
    }
}
