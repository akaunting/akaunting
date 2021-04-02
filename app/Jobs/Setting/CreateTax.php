<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Models\Setting\Tax;

class CreateTax extends Job
{
    protected $tax;

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
     * @return Tax
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->tax = Tax::create($this->request->all());
        });

        return $this->tax;
    }
}
