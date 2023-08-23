<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Events\Banking\AccountDeleting;
use App\Events\Banking\AccountDeleted;
use App\Interfaces\Job\ShouldDelete;

class DeleteAccount extends Job implements ShouldDelete
{
    public function handle(): bool
    {
        $this->authorize();

        event(new AccountDeleting($this->model));

        \DB::transaction(function () {
            $this->model->delete();
        });

        event(new AccountDeleted($this->model));

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
            'transactions' => 'transactions',
            'reconciliations' => 'reconciliations',
        ];

        $relationships = $this->countRelationships($this->model, $rels);

        if ($this->model->id == setting('default.account')) {
            $relationships[] = strtolower(trans_choice('general.companies', 1));
        }

        return $relationships;
    }
}
