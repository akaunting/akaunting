<?php

namespace App\Jobs\Banking;

use App\Abstracts\Job;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Banking\Account;

class UpdateAccount extends Job implements ShouldUpdate
{
    public function handle(): Account
    {
        $this->authorize();

        \DB::transaction(function () {
            $this->model->update($this->request->all());

            // Set default account
            if ($this->request['default_account']) {
                setting()->set('default.account', $this->model->id);
                setting()->save();
            }
        });

        return $this->model;
    }

    /**
     * Determine if this action is applicable.
     */
    public function authorize(): void
    {
        $relationships = $this->getRelationships();

        if (!$this->request->get('enabled') && ($this->model->id == setting('default.account'))) {
            $relationships[] = strtolower(trans_choice('general.companies', 1));

            $message = trans('messages.warning.disabled', ['name' => $this->model->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }

        if (!$relationships) {
            return;
        }

        if ($this->model->currency_code != $this->request->get('currency_code')) {
            $message = trans('messages.warning.disable_code', ['name' => $this->model->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships(): array
    {
        $rels = [
            'transactions' => 'transactions',
        ];

        return $this->countRelationships($this->model, $rels);
    }
}
