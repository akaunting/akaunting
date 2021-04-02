<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentRecurring as Event;
use App\Notifications\Sale\Invoice as Notification;

class SendDocumentRecurringNotification
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

        // Notify the customer
        if ($document->contact && !empty($document->contact_email)) {
            $document->contact->notify(new Notification($document, "{$document->type}_recur_customer"));
        }

        // Notify all users assigned to this company
        foreach ($document->company->users as $user) {
            if (!$user->can('read-notifications')) {
                continue;
            }

            $user->notify(new Notification($document, "{$document->type}_recur_admin"));
        }
    }
}
