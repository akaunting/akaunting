<?php

namespace App\Listeners\Sale;

use App\Events\Sale\InvoiceSent as Event;
use App\Jobs\Sale\CreateInvoiceHistory;
use App\Traits\Jobs;

class MarkInvoiceSent
{
    use Jobs;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->invoice->status != 'partial') {
            $event->invoice->status = 'sent';

            $event->invoice->save();
        }

        $this->dispatch(new CreateInvoiceHistory($event->invoice, 0, trans('invoices.messages.marked_sent')));
    }
}
