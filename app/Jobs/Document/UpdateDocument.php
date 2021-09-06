<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Events\Document\PaidAmountCalculated;
use App\Events\Document\DocumentUpdated;
use App\Events\Document\DocumentUpdating;
use App\Interfaces\Job\ShouldUpdate;
use App\Jobs\Document\CreateDocumentItemsAndTotals;
use App\Models\Document\Document;
use App\Traits\Relationships;
use Illuminate\Support\Str;

class UpdateDocument extends Job implements ShouldUpdate
{
    use Relationships;

    public function handle(): Document
    {
        if (empty($this->request['amount'])) {
            $this->request['amount'] = 0;
        }

        event(new DocumentUpdating($this->model, $this->request));

        \DB::transaction(function () {
            // Upload attachment
            if ($this->request->file('attachment')) {
                $this->deleteMediaModel($this->model, 'attachment', $this->request);

                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, Str::plural($this->model->type));

                    $this->model->attachMedia($media, 'attachment');
                }
            } elseif (!$this->request->file('attachment') && $this->model->attachment) {
                $this->deleteMediaModel($this->model, 'attachment', $this->request);
            }

            $this->deleteRelationships($this->model, ['items', 'item_taxes', 'totals']);

            $this->dispatch(new CreateDocumentItemsAndTotals($this->model, $this->request));

            $this->model->paid_amount = $this->model->paid;

            event(new PaidAmountCalculated($this->model));

            if ($this->model->paid_amount > 0) {
                if ($this->request['amount'] == $this->model->paid_amount) {
                    $this->request['status'] = 'paid';
                }

                if ($this->request['amount'] > $this->model->paid_amount) {
                    $this->request['status'] = 'partial';
                }
            }

            unset($this->model->reconciled);
            unset($this->model->paid_amount);

            $this->model->update($this->request->all());

            $this->model->updateRecurring($this->request->all());
        });

        event(new DocumentUpdated($this->model, $this->request));

        return $this->model;
    }
}
