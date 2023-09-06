<?php

namespace Bugsnag\Middleware;

use Bugsnag\Report;

class CallbackBridge
{
    /**
     * The callback to run.
     *
     * @var callable
     */
    protected $callback;

    /**
     * Create a new callback bridge middleware instance.
     *
     * @param callable $callback the callback to run
     *
     * @return void
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Execute the add callback bridge middleware.
     *
     * @param \Bugsnag\Report $report the bugsnag report instance
     * @param callable        $next   the next stage callback
     *
     * @return void
     */
    public function __invoke(Report $report, callable $next)
    {
        $initialUnhandled = $report->getUnhandled();
        $initialSeverity = $report->getSeverity();
        $initialReason = $report->getSeverityReason();

        $callback = $this->callback;

        if ($callback($report) !== false) {
            $report->setUnhandled($initialUnhandled);
            if ($report->getSeverity() != $initialSeverity) {
                // Severity has been changed via callbacks -> severity reason should be userCallbackSetSeverity
                $report->setSeverityReason([
                    'type' => 'userCallbackSetSeverity',
                ]);
            } else {
                // Otherwise we ensure the original severity reason is preserved
                $report->setSeverityReason($initialReason);
            }

            $next($report);
        }
    }
}
