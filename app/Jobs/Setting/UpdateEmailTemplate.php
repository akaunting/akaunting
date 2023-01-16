<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Setting\EmailTemplate;

class UpdateEmailTemplate extends Job implements ShouldUpdate
{
    public function handle(): EmailTemplate
    {
        \DB::transaction(function () {
            $this->model->update($this->request->all());
        });

        return $this->model;
    }
}
