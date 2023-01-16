<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Models\Document\Document;

class CancelDocument extends Job
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
            $this->deleteRelationships($this->model, [
                'transactions', 'recurring'
            ]);

            $this->model->status = 'cancelled';
            $this->model->save();
        });

        return $this->model;
    }
}
