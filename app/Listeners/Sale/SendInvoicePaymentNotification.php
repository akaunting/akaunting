<?php

namespace App\Listeners\Sale;

use App\Events\Sale\PaymentReceived as Event;
use App\Notifications\Portal\PaymentReceived as Notification;

class SendInvoicePaymentNotification
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
        $transaction = $invoice->transactions()->latest()->first();

        // Notify the customer
        if ($invoice->contact && !empty($invoice->contact_email)) {
            $invoice->contact->notify(new Notification($invoice, $transaction, 'invoice_payment_customer'));
        }

        // Notify all users assigned to this company
        foreach ($invoice->company->users as $user) {
            if (!$user->can('read-notifications')) {
                continue;
            }

            $user->notify(new Notification($invoice, $transaction, 'invoice_payment_admin'));
        }
    }
}
