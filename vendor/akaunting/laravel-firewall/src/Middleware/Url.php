<?php

namespace Akaunting\Firewall\Middleware;

use Akaunting\Firewall\Abstracts\Middleware;
use Akaunting\Firewall\Events\AttackDetected;

class Url extends Middleware
{
    public function check($patterns)
    {
        $protected = false;

        if (! $inspections = config('firewall.middleware.' . $this->middleware . '.inspections')) {
            return $protected;
        }

        foreach ($inspections as $inspection) {
            if (! $this->request->is($inspection)) {
                continue;
            }

            $protected = true;

            break;
        }

        if ($protected) {
            $log = $this->log();

            event(new AttackDetected($log));
        }

        return $protected;
    }
}
