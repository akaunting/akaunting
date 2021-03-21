<?php

namespace Akaunting\Firewall\Middleware;

use Akaunting\Firewall\Abstracts\Middleware;
use Akaunting\Firewall\Events\AttackDetected;
use Jenssegers\Agent\Agent;

class Bot extends Middleware
{
    public function check($patterns)
    {
        $agent = new Agent();

        if (!$agent->isRobot()) {
            return false;
        }

        if (!$crawlers = config('firewall.middleware.' . $this->middleware . '.crawlers')) {
            return false;
        }

        $status = false;

        if (!empty($crawlers['allow']) && !in_array((string) $agent->robot(), (array) $crawlers['allow'])) {
            $status = true;
        }

        if (in_array((string) $agent->robot(), (array) $crawlers['block'])) {
            $status = true;
        }

        if ($status) {
            $log = $this->log();

            event(new AttackDetected($log));
        }

        return $status;
    }
}
