<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Events\Setting\TaxCreated;
use App\Events\Setting\TaxCreating;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Setting\Tax;

class CreateTax extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Tax
    {
        event(new TaxCreating($this->request));

        \DB::transaction(function () {
            $this->model = Tax::create($this->request->all());
        });

        event(new TaxCreated($this->model, $this->request));

        return $this->model;
    }
}
