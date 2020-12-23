<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentReminded as Event;
use App\Models\Document\Document;
use App\Notifications\Sale\Invoice as InvoiceNotification;
use App\Notifications\Purchase\Bill as BillNotification;

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

        $notification = InvoiceNotification::class;
        if ($document->type === Document::BILL_TYPE) {
            $notification = BillNotification::class;
        }

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
