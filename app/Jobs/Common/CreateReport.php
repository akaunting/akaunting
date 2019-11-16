<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Common\Report;

class CreateReport extends Job
{
    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Report
     */
    public function handle()
    {
        $report = Report::create($this->request->all());

        return $report;
    }
}
