<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Events\Document\DocumentCreated;
use App\Events\Document\DocumentCreating;
use App\Jobs\Document\CreateDocumentItemsAndTotals;
use App\Models\Document\Document;
use Illuminate\Support\Str;

class CreateDocument extends Job
{
    protected $document;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
        $this->request->merge(['created_by' => user_id()]);
    }

    /**
     * Execute the job.
     *
     * @return Document
     */
    public function handle()
    {
        if (empty($this->request['amount'])) {
            $this->request['amount'] = 0;
        }

        event(new DocumentCreating($this->request));

        \DB::transaction(function () {
            $this->document = Document::create($this->request->all());

            // Upload attachment
            if ($this->request->file('attachment')) {
                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, Str::plural($this->document->type));

                    $this->document->attachMedia($media, 'attachment');
                }
            }

            $this->dispatch(new CreateDocumentItemsAndTotals($this->document, $this->request));

            $this->document->update($this->request->all());

            $this->document->createRecurring($this->request->all());
        });

        event(new DocumentCreated($this->document, $this->request));

        return $this->document;
    }
}
