<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Events\Setting\CategoryDeleted;
use App\Events\Setting\CategoryDeleting;
use App\Exceptions\Settings\LastCategoryDelete;
use App\Interfaces\Job\ShouldDelete;
use App\Models\Setting\Category;

class DeleteCategory extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        $this->authorize();

        event(new CategoryDeleting($this->model));

        \DB::transaction(function () {
            $this->model->delete();
        });

        event(new CategoryDeleted($this->model));

        return true;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        // Can not delete transfer category
        if ($this->model->isTransferCategory()) {
            $message = trans('messages.error.transfer_category', ['type' => $this->model->name]);

            throw new \Exception($message);
        }

        // Can not delete the last category by type
        if (Category::where('type', $this->model->type)->count() == 1) {
            $message = trans('messages.error.last_category', ['type' => strtolower(trans_choice('general.' . $this->model->type . 's', 1))]);

            throw new LastCategoryDelete($message);
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

        $relationships = $this->countRelationships($this->model, $rels);

        if ($this->model->id == setting('default.income_category')) {
            $relationships[] = strtolower(trans_choice('general.incomes', 1));
        }

        if ($this->model->id == setting('default.expense_category')) {
            $relationships[] = strtolower(trans_choice('general.expenses', 1));
        }

        return $relationships;
    }
}
