<?php

namespace App\Listeners\Income;

use App\Events\Income\InvoiceViewed as Event;

class MarkInvoiceViewed
{
    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $invoice = $event->invoice;

        if ($invoice->invoice_status_code != 'sent') {
            return;
        }

        unset($invoice->paid);

        $invoice->invoice_status_code = 'viewed';
        $invoice->save();
    }
}
