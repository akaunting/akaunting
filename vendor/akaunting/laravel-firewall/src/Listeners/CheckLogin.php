<?php

namespace Akaunting\Firewall\Listeners;

use Akaunting\Firewall\Events\AttackDetected;
use Akaunting\Firewall\Traits\Helper;
use Illuminate\Auth\Events\Failed as Event;

class CheckLogin
{
    use Helper;

    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skip($event)) {
            return;
        }

        $this->request['password'] = '******';

        $log = $this->log('login');

        event(new AttackDetected($log));
    }

    public function skip($event)
    {
        $this->request = request();
        $this->user_id = 0;

        if ($this->isDisabled('login')) {
            return true;
        }

        if ($this->isWhitelist()) {
            return true;
        }
    }
}
