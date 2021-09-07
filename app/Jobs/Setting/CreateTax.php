<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Setting\Tax;

class CreateTax extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Tax
    {
        \DB::transaction(function () {
            $this->model = Tax::create($this->request->all());
        });

        return $this->model;
    }
}
