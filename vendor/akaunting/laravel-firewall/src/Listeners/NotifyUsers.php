<?php

namespace Akaunting\Firewall\Listeners;

use Akaunting\Firewall\Events\AttackDetected as Event;
use Akaunting\Firewall\Notifications\AttackDetected;
use Akaunting\Firewall\Notifications\Notifiable;
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
        try {
            (new Notifiable)->notify(new AttackDetected($event->log));
        } catch (Throwable $e) {
            report($e);
        }
    }
}
