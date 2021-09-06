<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;
use App\Models\Setting\Category;

class DeleteCategory extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        $this->authorize();

        \DB::transaction(function () {
            $this->model->delete();
        });

        return true;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        // Can not delete the last category by type
        if (Category::where('type', $this->model->type)->count() == 1) {
            $message = trans('messages.error.last_category', ['type' => strtolower(trans_choice('general.' . $this->model->type . 's', 1))]);

            throw new \Exception($message);
        }

        if ($relationships = $this->getRelationships()) {
            $message = trans('messages.warning.deleted', ['name' => $this->model->name, 'text' => implode(', ', $relationships)]);

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
