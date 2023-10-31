<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Events\Setting\TaxDeleted;
use App\Events\Setting\TaxDeleting;
use App\Interfaces\Job\ShouldDelete;

class DeleteTax extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        $this->authorize();

        event(new TaxDeleting($this->model));

        \DB::transaction(function () {
            $this->model->delete();
        });

        event(new TaxDeleted($this->model));

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
            'items' => 'items',
            'invoice_items' => 'invoices',
            'bill_items' => 'bills',
        ];

        return $this->countRelationships($this->model, $rels);
    }
}
