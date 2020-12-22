<?php

namespace App\Listeners\Sale;

use App\Events\Sale\InvoiceViewed as Event;
use App\Jobs\Sale\CreateInvoiceHistory;
use App\Traits\Jobs;

class MarkInvoiceViewed
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
        $invoice = $event->invoice;

        if ($invoice->status != 'sent') {
            return;
        }

        unset($invoice->paid);

        $invoice->status = 'viewed';
        $invoice->save();

        $this->dispatch(new CreateInvoiceHistory($event->invoice, 0, trans('invoices.messages.marked_viewed')));
    }
}
