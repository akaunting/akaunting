<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Common\EmailTemplate;

class CreateEmailTemplate extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): EmailTemplate
    {
        \DB::transaction(function () {
            $this->model = EmailTemplate::create($this->request->all());
        });

        return $this->model;
    }
}
