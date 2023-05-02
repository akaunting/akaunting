<?php

namespace App\Listeners\Email;

use Akaunting\Firewall\Events\AttackDetected;
use Akaunting\Firewall\Traits\Helper;
use App\Events\Email\TooManyEmailsSent as Event;
use Illuminate\Support\Facades\Config;

class TellFirewallTooManyEmailsSent
{
    use Helper;

    public function handle(Event $event): void
    {
        $this->request = request();
        $this->middleware = 'too_many_emails_sent';
        $this->user_id = $event->user_id;

        $this->loadConfig();

        if ($this->skip($event)) {
            return;
        }

        $log = $this->log();

        event(new AttackDetected($log));
    }

    public function loadConfig(): void
    {
        if (! empty(Config::get('firewall.middleware.' . $this->middleware))) {
            return;
        }

        $config = array_merge_recursive(
            Config::get('firewall'),
            [
                'middleware' => [
                    $this->middleware => [
                        'enabled' => env('FIREWALL_MIDDLEWARE_' . strtoupper($this->middleware) . '_ENABLED', Config::get('firewall.enabled', true)),

                        'methods' => ['post'],

                        'routes' => [
                            'only' => [], // i.e. 'contact'
                            'except' => [], // i.e. 'admin/*'
                        ],

                        'auto_block' => [
                            'attempts' => env('FIREWALL_MIDDLEWARE_' . strtoupper($this->middleware) . '_AUTO_BLOCK_ATTEMPTS', 20),
                            'frequency' => 1 * 60, // 1 minute
                            'period' => 30 * 60, // 30 minutes
                        ],
                    ],
                ],
            ]
        );

        Config::set('firewall', $config);
    }

    public function skip($event): bool
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
