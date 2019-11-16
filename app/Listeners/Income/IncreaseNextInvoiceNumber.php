<?php

namespace App\Listeners\Income;

use App\Events\Income\InvoiceCreated as Event;
use App\Traits\Incomes;

class IncreaseNextInvoiceNumber
{
    use Incomes;

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
