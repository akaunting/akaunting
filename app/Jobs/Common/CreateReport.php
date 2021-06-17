<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Common\Report;

class CreateReport extends Job
{
    protected $report;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
        $this->request->merge(['created_by' => user_id()]);
    }

    /**
     * Execute the job.
     *
     * @return Report
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->report = Report::create($this->request->all());
        });

        return $this->report;
    }
}
