<?php

namespace App\Jobs\Document;

use App\Abstracts\Job;
use App\Models\Document\Document;
use Illuminate\Support\Str;

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
        $this->authorize();

        \DB::transaction(function () {
            $this->deleteRelationships($this->model, [
                'transactions', 'recurring'
            ]);

            $this->model->status = 'cancelled';
            $this->model->save();
        });

        return $this->model;
    }

    /**
     * Determine if this action is applicable.
     * Mirrors the same guard in DeleteDocument to prevent cancelling a document
     * that has reconciled transactions, which would silently destroy bank records.
     */
    public function authorize(): void
    {
        if ($this->model->transactions()->isReconciled()->count()) {
            $type = Str::plural($this->model->type);
            $message = trans('messages.warning.reconciled_doc', ['type' => trans_choice("general.$type", 1)]);

            throw new \Exception($message);
        }
    }
}
