<?php

namespace Spatie\LaravelIgnition\FlareMiddleware;

use Spatie\FlareClient\FlareMiddleware\FlareMiddleware;
use Spatie\FlareClient\Report;
use Spatie\LaravelIgnition\Recorders\JobRecorder\JobRecorder;

class AddJobs implements FlareMiddleware
{
    protected JobRecorder $jobRecorder;

    public function __construct()
    {
        $this->jobRecorder = app(JobRecorder::class);
    }

    public function handle(Report $report, $next)
    {
        if ($job = $this->jobRecorder->getJob()) {
            $report->group('job', $job);
        }

        return $next($report);
    }
}
