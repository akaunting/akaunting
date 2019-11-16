<?php

namespace App\Listeners\Expense;

use App\Events\Expense\BillRecurring as Event;
use App\Notifications\Expense\Bill as Notification;

class SendBillRecurringNotification
{
    /**
     * Handle the event.
     *
     * @param  $event
     * @return array
     */
    public function handle(Event $event)
    {
        $bill = $event->bill;

        // Notify all users assigned to this company
        foreach ($bill->company->users as $user) {
            if (!$user->can('read-notifications')) {
                continue;
            }

            $user->notify(new Notification($bill, 'bill_recur_admin'));
        }
    }
}
