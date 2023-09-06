<?php

namespace Spatie\FlareClient\FlareMiddleware;

use Spatie\FlareClient\Report;

class AddNotifierName implements FlareMiddleware
{
    public const NOTIFIER_NAME = 'Flare Client';

    public function handle(Report $report, $next)
    {
        $report->notifierName(static::NOTIFIER_NAME);

        return $next($report);
    }
}
