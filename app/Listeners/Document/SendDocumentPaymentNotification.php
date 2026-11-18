<?php

namespace App\Listeners\Document;

use App\Events\Document\PaymentReceived as Event;
use App\Notifications\Portal\PaymentReceived as Notification;

class SendDocumentPaymentNotification
{
    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->request['type'] !== 'income') {
            return;
        }

        $document = $event->document;
        $transaction = $document->transactions()->latest()->first();

        if (! $transaction) {
            return;
        }

        $notification = $this->getNotificationClass($document->type);

        // Notify the customer
        if ($document->contact && !empty($document->contact_email)) {
            $document->contact->notify(new $notification($document, $transaction, "{$document->type}_payment_customer"), true);
        }

        // Notify all users assigned to this company
        foreach ($document->company->users as $user) {
            if ($user->cannot('read-notifications')) {
                continue;
            }

            $user->notify(new $notification($document, $transaction, "{$document->type}_payment_admin"));
        }
    }

    /**
     * A module's document type can bring its own class, for its own tags, links and texts. It has to be one of
     * core's, as it is built and sent the same way, so a class that is missing, such as one of an app removed
     * without its settings, or one of another kind falls back to core's and the payment is still notified.
     */
    protected function getNotificationClass(string $type): string
    {
        $class = config('type.document.' . $type . '.notification.payment_class');

        if (empty($class) || ! is_string($class) || ! class_exists($class) || ! is_a($class, Notification::class, true)) {
            return Notification::class;
        }

        return $class;
    }
}
