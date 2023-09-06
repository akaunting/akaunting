<?php

namespace Bugsnag\BugsnagLaravel\Middleware;

use Bugsnag\BugsnagLaravel\Internal\BacktraceProcessor;
use Bugsnag\Report;

class UnhandledState
{
    /**
     * Execute the unhandled state middleware.
     *
     * @param \Bugsnag\Report $report the bugsnag report instance
     * @param callable        $next   the next stage callback
     *
     * @return void
     */
    public function __invoke(Report $report, callable $next)
    {
        $stackFrames = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);

        if (!is_array($stackFrames)) {
            $stackFrames = [];
        }

        $backtraceProcessor = new BacktraceProcessor($stackFrames);

        if ($backtraceProcessor->isUnhandled()) {
            $report->setUnhandled(true);
            $report->setSeverityReason([
                'type' => 'unhandledExceptionMiddleware',
                'attributes' => ['framework' => 'Laravel'],
            ]);
        }

        $next($report);
    }
}
