<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Models\Common\Contact;

class DuplicateContact extends Job
{
    protected $clone;

    public function __construct(Contact $model)
    {
        $this->model = $model;

        parent::__construct($model);
    }

    public function handle(): Contact
    {
        \DB::transaction(function () {
            $this->clone = $this->model->duplicate();
        });

        return $this->clone;
    }
}
