<?php

namespace Akaunting\Firewall\Events;

class AttackDetected
{
    public $log;

    /**
     * Create a new event instance.
     */
    public function __construct($log)
    {
        $this->log = $log;
    }
}
