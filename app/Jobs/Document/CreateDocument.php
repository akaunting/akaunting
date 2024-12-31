<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Events\Document\DocumentCreated;
use App\Events\Document\DocumentCreating;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Jobs\Document\CreateDocumentItemsAndTotals;
use App\Models\Document\Document;
use App\Traits\Plans;
use Illuminate\Support\Str;

class CreateDocument extends Job implements HasOwner, HasSource, ShouldCreate
{
    use Plans;

    public function handle(): Document
    {
        $this->authorize();

        if (empty($this->request['amount'])) {
            $this->request['amount'] = 0;
        }

        // Disable this lines for global discount issue fixed ( https://github.com/akaunting/akaunting/issues/2797 )
        if (! empty($this->request['discount'])) {
            $this->request['discount_rate'] = $this->request['discount'];
        }

        event(new DocumentCreating($this->request));

        \DB::transaction(function () {
            $this->model = Document::create($this->request->all());

            // Upload attachment
            if ($this->request->file('attachment')) {
                foreach ($this->request->file('attachment') as $attachment) {
                    $media = $this->getMedia($attachment, Str::plural($this->model->type));

                    $this->model->attachMedia($media, 'attachment');
                }
            }

            $this->dispatch(new CreateDocumentItemsAndTotals($this->model, $this->request));

            $this->model->update($this->request->all());

            $this->model->createRecurring($this->request->all());
        });

        event(new DocumentCreated($this->model, $this->request));

        return $this->model;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        $limit = $this->getAnyActionLimitOfPlan();
        if (! $limit->action_status && ! empty($this->request['type']) && ($this->request['type'] == 'invoice')) {
            throw new \Exception($limit->message);
        }
    }
}
