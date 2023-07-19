<?php

namespace App\Listeners\Document;

use App\Events\Document\DocumentViewed as Event;
use App\Traits\Documents;

class SendDocumentViewNotification
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

        if (in_array($document->status, [
            'viewed', 'approved', 'received', 'refused', 'partial', 'paid',
            'cancelled', 'voided', 'completed', 'refunded',
        ])) {
            return;
        }

        $config = config('type.document.' . $document->type . '.notification');

        if (empty($config) || empty($config['class'])) {
            return;
        }

        // Check if should notify users
        if (! $config['notify_user']) {
            return;
        }

        $notification = $config['class'];

        // Notify all users assigned to this company
        foreach ($document->company->users as $user) {
            if ($user->cannot('read-notifications')) {
                continue;
            }

            $user->notify(new $notification($document, "{$document->type}_view_admin"));
        }
    }
}
