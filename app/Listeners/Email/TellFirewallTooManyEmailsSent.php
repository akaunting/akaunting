<?php

namespace App\Listeners\Email;

use Akaunting\Firewall\Events\AttackDetected;
use Akaunting\Firewall\Traits\Helper;
use App\Events\Email\TooManyEmailsSent as Event;

class TellFirewallTooManyEmailsSent
{
    use Helper;

    public function handle(Event $event): void
    {
        $this->request = request();
        $this->middleware = 'too_many_emails_sent';
        $this->user_id = $event->user_id;

        if ($this->skip()) {
            return;
        }

        $log = $this->log();

        event(new AttackDetected($log));
    }

    public function skip(): bool
    {
        if ($this->isDisabled()) {
            return true;
        }

        if ($this->isWhitelist()) {
            return true;
        }

        return false;
    }
}
