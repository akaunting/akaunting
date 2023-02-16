<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Events\Document\DocumentCancelled;
use App\Events\Document\DocumentCancelling;
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
        event(new DocumentCancelling($this->model));

        \DB::transaction(function () {
            $this->deleteRelationships($this->model, [
                'transactions', 'recurring'
            ]);

            $this->model->status = 'cancelled';
            $this->model->save();
        });

        $type_text = '';

        if ($alias = config('type.document.' . $this->model->type . '.alias', '')) {
            $type_text .= $alias . '::';
        }

        $type_text .= 'general.' . config('type.document.' . $this->model->type .'.translation.prefix');

        $type = trans_choice($type_text, 1);

        $this->dispatch(
            new CreateDocumentHistory(
                $this->model,
                0,
                trans('documents.messages.marked_cancelled', ['type' => $type])
            )
        );

        event(new DocumentCancelled($this->model));

        return $this->model;
    }
}
