<?php

namespace Akaunting\Firewall\Notifications;

use Illuminate\Notifications\Notifiable as NotifiableTrait;

class Notifiable
{
    use NotifiableTrait;

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
}
