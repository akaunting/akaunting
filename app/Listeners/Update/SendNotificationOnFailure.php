<?php

namespace App\Listeners\Update;

use App\Events\Install\UpdateFailed as Event;
use App\Notifications\Install\UpdateFailed as Notification;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Notifications\Notifiable;

class SendNotificationOnFailure
{
    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $notifiable = $this->getNotifiableClass();

        try {
            $notifiable->notify(new Notification($event));
        } catch (Exception | RequestException $e) {
            report($e);
        }
    }

    protected function getNotifiableClass()
    {
        return new class() {
            use Notifiable;

            public function routeNotificationForMail()
            {
                return config('update.notifications.mail.to');
            }

            public function routeNotificationForSlack()
            {
                return config('update.notifications.slack.to');
            }

            public function getKey()
            {
                return 1;
            }
        };
    }
}
