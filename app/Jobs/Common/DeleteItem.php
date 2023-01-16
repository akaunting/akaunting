<?php

namespace App\Jobs\Common;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldDelete;

class DeleteItem extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        $this->authorize();

        \DB::transaction(function () {
            $this->deleteRelationships($this->model, ['taxes']);

            $this->model->delete();
        });

        return true;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        if ($relationships = $this->getRelationships()) {
            $message = trans('messages.warning.deleted', ['name' => $this->model->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships(): array
    {
        $rels = [
            'invoice_items' => 'invoices',
            'bill_items' => 'bills',
        ];

        return $this->countRelationships($this->model, $rels);
    }
}
