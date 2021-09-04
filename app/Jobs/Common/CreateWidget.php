<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Common\Widget;

class CreateWidget extends Job
{
    protected $widget;

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
     * @return Widget
     */
    public function handle()
    {
        $this->request['enabled'] = $this->request['enabled'] ?? 1;

        \DB::transaction(function () {
            $this->widget = Widget::create($this->request->all());
        });

        return $this->widget;
    }
}
