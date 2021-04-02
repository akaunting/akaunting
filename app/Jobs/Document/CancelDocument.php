<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Models\Document\Document;

class CancelDocument extends Job
{
    protected $document;

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
            $this->deleteRelationships($this->document, [
                'transactions', 'recurring'
            ]);

            $this->document->status = 'cancelled';
            $this->document->save();
        });

        return $this->document;
    }
}
