<?php

namespace Bugsnag\Middleware;

use Bugsnag\Configuration;
use Bugsnag\Report;

class NotificationSkipper
{
    /**
     * The config instance.
     *
     * @var \Bugsnag\Configuration
     */
    protected $config;

    /**
     * Create a new notification skipper middleware instance.
     *
     * @param \Bugsnag\Configuration $config the configuration instance
     *
     * @return void
     */
    public function __construct(Configuration $config)
    {
        $this->config = $config;
    }

    /**
     * Execute the notification skipper middleware.
     *
     * @param \Bugsnag\Report $report the bugsnag report instance
     * @param callable        $next   the next stage callback
     *
     * @return void
     */
    public function __invoke(Report $report, callable $next)
    {
        if (!$this->config->shouldNotify()) {
            return;
        }

        $next($report);
    }
}
