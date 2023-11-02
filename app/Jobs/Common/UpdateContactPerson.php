<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Common\ContactPerson;

class UpdateContactPerson extends Job implements ShouldUpdate
{
    public function handle(): ContactPerson
    {
        \DB::transaction(function () {
            $this->model->update($this->request->all());
        });

        return $this->model;
    }
}
