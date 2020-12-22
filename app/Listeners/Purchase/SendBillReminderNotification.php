<?php

namespace App\Listeners\Purchase;

use App\Events\Purchase\BillReminded as Event;
use App\Notifications\Purchase\Bill as Notification;

class SendBillReminderNotification
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

            $user->notify(new Notification($bill, 'bill_remind_admin'));
        }
    }
}
