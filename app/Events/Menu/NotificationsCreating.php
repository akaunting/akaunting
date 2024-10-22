<?php

namespace App\Events\Menu;

use App\Abstracts\Event;

class NotificationsCreating extends Event
{
    public $notifications;

    /**
     * Create a new event instance.
     *
     * @param $notifications
     */
    public function __construct($notifications)
    {
        $this->notifications = $notifications;
    }
}
