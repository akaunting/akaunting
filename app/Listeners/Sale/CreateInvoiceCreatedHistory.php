<?php

namespace App\Listeners\Sale;

use App\Events\Sale\InvoiceCreated as Event;
use App\Jobs\Sale\CreateInvoiceHistory;
use App\Traits\Jobs;

class CreateInvoiceCreatedHistory
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
        $message = trans('messages.success.added', ['type' => $event->invoice->invoice_number]);

        $this->dispatch(new CreateInvoiceHistory($event->invoice, 0, $message));
    }
}
