<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;

class DeleteReport extends Job
{
    protected $report;

    /**
     * Create a new job instance.
     *
     * @param  $report
     */
    public function __construct($report)
    {
        $this->report = $report;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->report->delete();
        });

        return true;
    }
}
