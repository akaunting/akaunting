<?php

namespace Bugsnag\Middleware;

use Bugsnag\Breadcrumbs\Recorder;
use Bugsnag\Report;

class BreadcrumbData
{
    /**
     * The recorder instance.
     *
     * @var \Bugsnag\Breadcrumbs\Recorder
     */
    protected $recorder;

    /**
     * Create a new breadcrumb data middleware instance.
     *
     * @param \Bugsnag\Breadcrumbs\Recorder $recorder the recorder instance
     *
     * @return void
     */
    public function __construct(Recorder $recorder)
    {
        $this->recorder = $recorder;
    }

    /**
     * Execute the breadcrumb data middleware.
     *
     * @param \Bugsnag\Report $report the bugsnag report instance
     * @param callable        $next   the next stage callback
     *
     * @return void
     */
    public function __invoke(Report $report, callable $next)
    {
        foreach ($this->recorder as $breadcrumb) {
            $report->addBreadcrumb($breadcrumb);
        }

        $next($report);
    }
}
