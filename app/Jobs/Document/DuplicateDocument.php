<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Events\Document\DocumentCreated;
use App\Models\Document\Document;

class DuplicateDocument extends Job
{
    protected $document;

    protected $clone;

    /**
     * Create a new job instance.
     *
     * @param  $document
     */
    public function __construct($document)
    {
        $this->document = $document;
    }

    /**
     * Execute the job.
     *
     * @return Document
     */
    public function handle()
    {
        \DB::transaction(function () {
            $this->clone = $this->document->duplicate();
        });

        event(new DocumentCreated($this->clone, request()));

        return $this->clone;
    }
}
