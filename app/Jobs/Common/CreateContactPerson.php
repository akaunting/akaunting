<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Common\ContactPerson;

class CreateContactPerson extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): ContactPerson
    {
        \DB::transaction(function () {
            $this->model = ContactPerson::create($this->request->all());
        });

        return $this->model;
    }
}
