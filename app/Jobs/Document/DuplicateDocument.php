<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Events\Document\DocumentCreated;
use App\Models\Document\Document;

class DuplicateDocument extends Job
{
    protected $clone;

    public function __construct(Document $model)
    {
        $this->model = $model;

        parent::__construct($model);
    }

    public function handle(): Document
    {
        \DB::transaction(function () {
            $this->clone = $this->model->duplicate();
        });

        event(new DocumentCreated($this->clone, request()));

        return $this->clone;
    }
}
