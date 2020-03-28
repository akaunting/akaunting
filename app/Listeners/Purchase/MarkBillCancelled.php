<?php

namespace App\Listeners\Purchase;

use App\Events\Purchase\BillCancelled as Event;
use App\Jobs\Purchase\CancelBill;
use App\Jobs\Purchase\CreateBillHistory;
use App\Traits\Jobs;

class MarkBillCancelled
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
        $this->dispatch(new CancelBill($event->bill));

        $this->dispatch(new CreateBillHistory($event->bill, 0, trans('bills.messages.marked_cancelled')));
    }
}
