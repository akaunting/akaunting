<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Common\Report;

class CreateReport extends Job implements HasOwner, ShouldCreate
{
    public function handle(): Report
    {
        \DB::transaction(function () {
            $this->model = Report::create($this->request->all());
        });

        return $this->model;
    }
}
