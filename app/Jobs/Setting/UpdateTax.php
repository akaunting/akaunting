<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Events\Setting\TaxUpdated;
use App\Events\Setting\TaxUpdating;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Setting\Tax;

class UpdateTax extends Job implements ShouldUpdate
{
    public function handle(): Tax
    {
        $this->authorize();

        event(new TaxUpdating($this->model, $this->request));

        \DB::transaction(function () {
            $this->model->update($this->request->all());
        });

        event(new TaxUpdated($this->model, $this->request));

        return $this->model;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        if (! $relationships = $this->getRelationships()) {
            return;
        }

        if ($this->request->has('type') && ($this->request->get('type') != $this->model->type)) {
            $message = trans('messages.error.change_type', ['text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }

        if ($this->request->has('enabled') && ! $this->request->get('enabled')) {
            $message = trans('messages.warning.disabled', ['name' => $this->model->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships(): array
    {
        $rels = [
            'items' => 'items',
            'invoice_items' => 'invoices',
            'bill_items' => 'bills',
        ];

        return $this->countRelationships($this->model, $rels);
    }
}
