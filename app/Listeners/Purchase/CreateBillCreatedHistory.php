<?php

namespace App\Listeners\Purchase;

use App\Events\Purchase\BillCreated as Event;
use App\Jobs\Purchase\CreateBillHistory;
use App\Traits\Jobs;

class CreateBillCreatedHistory
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
        $message = trans('messages.success.added', ['type' => $event->bill->bill_number]);

        $this->dispatch(new CreateBillHistory($event->bill, 0, $message));
    }
}
