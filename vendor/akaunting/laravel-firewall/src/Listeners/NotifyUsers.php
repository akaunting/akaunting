<?php

namespace Akaunting\Firewall\Listeners;

use Akaunting\Firewall\Events\AttackDetected as Event;
use Akaunting\Firewall\Notifications\AttackDetected as Notification;
use Exception;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Notifications\Notifiable;
use Throwable;

class NotifyUsers
{
    /**
     * Handle the event.
     *
     * @param Event $event
     *
     * @return void
     */
    public function handle(Event $event)
    {
        $notifiable = $this->getNotifiableClass();

        try {
            $notifiable->notify(new Notification($event->log));
        } catch (Exception | RequestException | Throwable $e) {
            report($e);
        }
    }

    protected function getNotifiableClass()
    {
        return new class() {
            use Notifiable;

            public function routeNotificationForMail()
            {
                return config('firewall.notifications.mail.to');
            }

            public function routeNotificationForSlack()
            {
                return config('firewall.notifications.slack.to');
            }

            public function getKey()
            {
                return 1;
            }
        };
    }
}
