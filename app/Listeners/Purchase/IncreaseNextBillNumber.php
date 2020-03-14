<?php

namespace App\Listeners\Purchase;

use App\Events\Purchase\BillCreated as Event;
use App\Traits\Purchases;

class IncreaseNextBillNumber
{
    use Purchases;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        // Update next bill number
        $this->increaseNextBillNumber();
    }
}
