<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Events\Document\DocumentCreated;
use App\Events\Document\DocumentDuplicating;
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
        event(new DocumentDuplicating($this->model));

        \DB::transaction(function () {
            $this->clone = $this->model->duplicate();
        });

        if (! $this->clone instanceof Document) {
            throw new \RuntimeException('DuplicateDocument: duplicate() did not return a Document.');
        }

        event(new DocumentCreated($this->clone, request()));

        return $this->clone;
    }
}
