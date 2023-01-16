<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Common\Report;

class UpdateReport extends Job implements ShouldUpdate
{
    public function handle(): Report
    {
        \DB::transaction(function () {
            $this->model->update($this->request->all());
        });

        return $this->model;
    }
}
