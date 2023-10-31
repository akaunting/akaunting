<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Events\Setting\CurrencyDeleted;
use App\Events\Setting\CurrencyDeleting;
use App\Interfaces\Job\ShouldDelete;

class DeleteCurrency extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        $this->authorize();

        event(new CurrencyDeleting($this->model));

        \DB::transaction(function () {
            $this->model->delete();
        });

        event(new CurrencyDeleted($this->model));

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
            'accounts' => 'accounts',
            'customers' => 'customers',
            'invoices' => 'invoices',
            'bills' => 'bills',
            'transactions' => 'transactions',
        ];

        $relationships = $this->countRelationships($this->model, $rels);

        if ($this->model->code == default_currency()) {
            $relationships[] = strtolower(trans_choice('general.companies', 1));
        }

        return $relationships;
    }
}
