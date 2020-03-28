<?php

namespace App\Listeners\Sale;

use App\Events\Sale\InvoiceCancelled as Event;
use App\Jobs\Sale\CancelInvoice;
use App\Jobs\Sale\CreateInvoiceHistory;
use App\Traits\Jobs;

class MarkInvoiceCancelled
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
        $this->dispatch(new CancelInvoice($event->invoice));

        $this->dispatch(new CreateInvoiceHistory($event->invoice, 0, trans('invoices.messages.marked_cancelled')));
    }
}
