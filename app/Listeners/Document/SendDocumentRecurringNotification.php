<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentRecurring as Event;

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
        $config = config('type.' . $document->type . '.notification');

        if (empty($config) || empty($config['class'])) {
            return;
        }

        $notification = $config['class'];

        // Notify the customer
        if ($config['notify_contact'] && $document->contact && !empty($document->contact_email)) {
            $document->contact->notify(new $notification($document, "{$document->type}_recur_customer"));
        }

        // Check if should notify users
        if (!$config['notify_user']) {
            return;
        }

        // Notify all users assigned to this company
        foreach ($document->company->users as $user) {
            if (!$user->can('read-notifications')) {
                continue;
            }

            $user->notify(new $notification($document, "{$document->type}_recur_admin"));
        }
    }
}
