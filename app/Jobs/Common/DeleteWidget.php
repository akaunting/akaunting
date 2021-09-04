<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;

class DeleteWidget extends Job
{
    protected $widget;

    /**
     * Create a new job instance.
     *
     * @param  $widget
     */
    public function __construct($widget)
    {
        $this->widget = $widget;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->widget->delete();
        });

        return true;
    }
}
