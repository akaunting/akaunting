<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Events\Document\PaidAmountCalculated;
use App\Events\Document\DocumentUpdated;
use App\Events\Document\DocumentUpdating;
use App\Jobs\Document\CreateDocumentItemsAndTotals;
use App\Models\Document\Document;
use App\Traits\Relationships;
use Illuminate\Support\Str;

class UpdateDocument extends Job
{
    use Relationships;

    protected $document;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($document, $request)
    {
        $this->document = $document;
        $this->request = $this->getRequestInstance($request);
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

        event(new DocumentUpdating($this->document, $this->request));

        \DB::transaction(function () {
            // Upload attachment
            if ($this->request->file('attachment')) {
                $this->deleteMediaModel($this->document, 'attachment', $this->request);

                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, Str::plural($this->document->type));

                    $this->document->attachMedia($media, 'attachment');
                }
            } elseif (!$this->request->file('attachment') && $this->document->attachment) {
                $this->deleteMediaModel($this->document, 'attachment', $this->request);
            }

            $this->deleteRelationships($this->document, ['items', 'item_taxes', 'totals']);

            $this->dispatch(new CreateDocumentItemsAndTotals($this->document, $this->request));

            $this->document->paid_amount = $this->document->paid;

            event(new PaidAmountCalculated($this->document));

            if ($this->document->paid_amount > 0) {
                if ($this->request['amount'] == $this->document->paid_amount) {
                    $this->request['status'] = 'paid';
                }

                if ($this->request['amount'] > $this->document->paid_amount) {
                    $this->request['status'] = 'partial';
                }
            }

            unset($this->document->reconciled);
            unset($this->document->paid_amount);

            $this->document->update($this->request->all());

            $this->document->updateRecurring($this->request->all());
        });

        event(new DocumentUpdated($this->document, $this->request));

        return $this->document;
    }
}
