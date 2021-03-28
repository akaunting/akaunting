<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Common\Report;

class UpdateReport extends Job
{
    protected $report;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $report
     * @param  $request
     */
    public function __construct($report, $request)
    {
        $this->report = $report;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Report
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->report->update($this->request->all());
        });

        return $this->report;
    }
}
