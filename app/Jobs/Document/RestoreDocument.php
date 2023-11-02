<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Models\Document\Document;

class RestoreDocument extends Job
{
    protected $model;

    public function __construct(Document $model)
    {
        $this->model = $model;

        parent::__construct($model);
    }

    public function handle(): Document
    {
        \DB::transaction(function () {
            $this->model->status = 'draft';
            $this->model->save();
        });

        return $this->model;
    }
}
