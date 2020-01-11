<?php

namespace App\Listeners\Sale;

use App\Events\Sale\InvoiceViewed as Event;

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

        if ($invoice->status != 'sent') {
            return;
        }

        unset($invoice->paid);

        $invoice->status = 'viewed';
        $invoice->save();
    }
}
