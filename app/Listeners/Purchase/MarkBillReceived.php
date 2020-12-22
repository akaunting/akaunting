<?php

namespace App\Listeners\Purchase;

use App\Events\Purchase\BillReceived as Event;
use App\Jobs\Purchase\CreateBillHistory;
use App\Traits\Jobs;

class MarkBillReceived
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
        if ($event->bill->status != 'partial') {
            $event->bill->status = 'received';

            $event->bill->save();
        }

        $this->dispatch(new CreateBillHistory($event->bill, 0, trans('bills.messages.marked_received')));
    }
}
