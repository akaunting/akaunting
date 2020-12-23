<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Models\Document\DocumentHistory;

class CreateDocumentHistory extends Job
{
    protected $document;

    protected $notify;

    protected $description;

    /**
     * Create a new job instance.
     *
     * @param  $document
     * @param  $notify
     * @param  $description
     */
    public function __construct($document, $notify = 0, $description = null)
    {
        $this->document = $document;
        $this->notify = $notify;
        $this->description = $description;
    }

    /**
     * Execute the job.
     *
     * @return DocumentHistory
     */
    public function handle()
    {
        $description = $this->description ?: trans_choice('general.payments', 1);

        $document_history = DocumentHistory::create([
            'company_id' => $this->document->company_id,
            'type' => $this->document->type,
            'document_id' => $this->document->id,
            'status' => $this->document->status,
            'notify' => $this->notify,
            'description' => $description,
        ]);

        return $document_history;
    }
}
