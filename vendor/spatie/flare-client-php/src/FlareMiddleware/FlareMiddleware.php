<?php

namespace Spatie\FlareClient\FlareMiddleware;

use Closure;
use Spatie\FlareClient\Report;

interface FlareMiddleware
{
    public function handle(Report $report, Closure $next);
}
