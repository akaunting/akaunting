<?php

namespace Akaunting\Firewall\Middleware;

use Akaunting\Firewall\Abstracts\Middleware;

class Whitelist extends Middleware
{
    public function check($patterns)
    {
        return ($this->isWhitelist() === false);
    }
}
