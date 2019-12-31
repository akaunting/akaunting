<?php

namespace App\Listeners\Sale;

use App\Events\Sale\InvoiceRecurring as Event;
use App\Notifications\Sale\Invoice as Notification;

class SendInvoiceRecurringNotification
{
    /**
     * Handle the event.
     *
     * @param  $event
     * @return array
     */
    public function handle(Event $event)
    {
        $invoice = $event->invoice;

        // Notify the customer
        if ($invoice->contact && !empty($invoice->contact_email)) {
            $invoice->contact->notify(new Notification($invoice, 'invoice_recur_customer'));
        }

        // Notify all users assigned to this company
        foreach ($invoice->company->users as $user) {
            if (!$user->can('read-notifications')) {
                continue;
            }

            $user->notify(new Notification($invoice, 'invoice_recur_admin'));
        }
    }
}
