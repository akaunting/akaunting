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
            $this->deleteSubCategories($this->model);

            $this->model->delete();
        });

        event(new CategoryDeleted($this->model));

        return true;
    }

    public function deleteSubCategories($model)
    {
        $model->delete();

        foreach ($model->sub_categories as $sub_category) {
            $this->deleteSubCategories($sub_category);
        }
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
        if (Category::where('type', $this->model->type)->count() == 1 && $this->model->parent_id === null) {
            $message = trans('messages.error.last_category', ['type' => strtolower(trans_choice('general.' . $this->model->type . 's', 1))]);

            throw new LastCategoryDelete($message);
        }

        if ($relationships = $this->getRelationships()) {
            $message = trans('messages.warning.deleted', ['name' => $this->model->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }

        foreach ($this->model->sub_categories as $sub_category) {
            $this->getSubCategoryRelationships($sub_category);
        }
    }

    public function getRelationships($model = null): array
    {
        if (! $model) {
            $model = $this->model;
        } 

        $rels = [
            'items' => 'items',
            'invoices' => 'invoices',
            'bills' => 'bills',
            'transactions' => 'transactions',
        ];

        $relationships = $this->countRelationships($model, $rels);

        if ($model->id == setting('default.income_category')) {
            $relationships[] = strtolower(trans_choice('general.incomes', 1));
        }

        if ($model->id == setting('default.expense_category')) {
            $relationships[] = strtolower(trans_choice('general.expenses', 1));
        }

        return $relationships;
    }

    public function getSubCategoryRelationships($model)
    {
        if ($relationships = $this->getRelationships($model)) {
            $message = trans('messages.warning.deleted', ['name' => $model->name, 'text' => implode(', ', $relationships)]);
    
            throw new \Exception($message);
        }

        foreach ($model->sub_categories as $sub_category) {
            $this->getSubCategoryRelationships($sub_category);
        }
    }
}
