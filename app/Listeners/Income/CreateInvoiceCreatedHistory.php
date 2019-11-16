<?php

namespace App\Listeners\Income;

use App\Events\Income\InvoiceCreated as Event;
use App\Jobs\Income\CreateInvoiceHistory;
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
