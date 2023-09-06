<?php

namespace Akaunting\Firewall\Middleware;

use Akaunting\Firewall\Abstracts\Middleware;
use Akaunting\Firewall\Events\AttackDetected;
use Jenssegers\Agent\Agent as Parser;

class Agent extends Middleware
{
    protected $parser;

    public function check($patterns)
    {
        $status = false;

        $this->parser = new Parser();

        if ($this->isMalicious()) {
            $status = true;
        }

        if (! $status && $this->isBrowser()) {
            $status = true;
        }

        if (! $status && $this->isPlatform()) {
            $status = true;
        }

        if (! $status && $this->isDevice()) {
            $status = true;
        }

        if (! $status && $this->isProperty()) {
            $status = true;
        }

        if ($status) {
            $log = $this->log();

            event(new AttackDetected($log));
        }

        return $status;
    }

    protected function isMalicious()
    {
        $agent = $this->parser->getUserAgent();

        if (empty($agent) || ($agent == '-') || strstr($agent, '<?')) {
            return true;
        }

        $patterns = [
            '@"feed_url@',
            '@}__(.*)|O:@',
            '@J?Simple(p|P)ie(Factory)?@',
        ];

        foreach ($patterns as $pattern) {
            if (! preg_match($pattern, $agent) == 1) {
                continue;
            }

            return true;
        }

        return false;
    }

    protected function isBrowser()
    {
        if (! $browsers = config('firewall.middleware.' . $this->middleware . '.browsers')) {
            return false;
        }

        if (! empty($browsers['allow']) && ! in_array((string) $this->parser->browser(), (array) $browsers['allow'])) {
            return true;
        }

        if (in_array((string) $this->parser->browser(), (array) $browsers['block'])) {
            return true;
        }

        return false;
    }

    protected function isPlatform()
    {
        if (! $platforms = config('firewall.middleware.' . $this->middleware . '.platforms')) {
            return false;
        }

        if (! empty($platforms['allow']) && ! in_array((string) $this->parser->platform(), (array) $platforms['allow'])) {
            return true;
        }

        if (in_array((string) $this->parser->platform(), (array) $platforms['block'])) {
            return true;
        }

        return false;
    }

    protected function isDevice()
    {
        if (! $devices = config('firewall.middleware.' . $this->middleware . '.devices')) {
            return false;
        }

        $list = ['Desktop', 'Mobile', 'Tablet'];

        foreach ((array) $devices['allow'] as $allow) {
            if (! in_array($allow, $list)) {
                continue;
            }

            $function = 'is' . ucfirst($allow);

            if ($this->parser->$function()) {
                continue;
            }

            return true;
        }

        foreach ((array) $devices['block'] as $block) {
            if (! in_array($block, $list)) {
                continue;
            }

            $function = 'is' . ucfirst($block);

            if (! $this->parser->$function()) {
                continue;
            }

            return true;
        }

        return false;
    }

    protected function isProperty()
    {
        if (! $agents = config('firewall.middleware.' . $this->middleware . '.properties')) {
            return false;
        }

        foreach ((array) $agents['allow'] as $allow) {
            if ($this->parser->is((string) $allow)) {
                continue;
            }

            return true;
        }

        foreach ((array) $agents['block'] as $block) {
            if (! $this->parser->is((string) $block)) {
                continue;
            }

            return true;
        }

        return false;
    }
}
