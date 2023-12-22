<?php

namespace App\Listeners\Banking;

use App\Events\Banking\TransactionCreated as Event;
use App\Traits\Transactions;

class IncreaseNextTransactionNumber
{
    use Transactions;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $suffix = $event->transaction->isRecurringTransaction() ? '-recurring' : '';

        // Update next transaction number
        $this->increaseNextTransactionNumber($event->transaction->type, $suffix);
    }
}
