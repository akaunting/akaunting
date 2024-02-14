<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Events\Setting\CategoryUpdated;
use App\Events\Setting\CategoryUpdating;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Setting\Category;

class UpdateCategory extends Job implements ShouldUpdate
{
    public function handle(): Category
    {
        $this->authorize();

        event(new CategoryUpdating($this->model, $this->request));

        \DB::transaction(function () {
            $this->model->update($this->request->all());
        });

        event(new CategoryUpdated($this->model, $this->request));

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
            'invoices' => 'invoices',
            'bills' => 'bills',
            'transactions' => 'transactions',
        ];

        return $this->countRelationships($this->model, $rels);
    }
}
