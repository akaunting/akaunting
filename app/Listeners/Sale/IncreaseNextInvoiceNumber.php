<?php

namespace App\Listeners\Sale;

use App\Events\Sale\InvoiceCreated as Event;
use App\Traits\Sales;

class IncreaseNextInvoiceNumber
{
    use Sales;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        // Update next invoice number
        $this->increaseNextInvoiceNumber();
    }
}
