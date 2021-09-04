<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Common\Widget;

class UpdateItem extends Job
{
    protected $widget;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $widget
     * @param  $request
     */
    public function __construct($widget, $request)
    {
        $this->widget = $widget;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Item
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->widget->update($this->request->all());
        });

        return $this->widget;
    }
}
