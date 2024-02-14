<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Events\Setting\CurrencyUpdated;
use App\Events\Setting\CurrencyUpdating;
use App\Interfaces\Job\ShouldUpdate;
use App\Models\Setting\Currency;

class UpdateCurrency extends Job implements ShouldUpdate
{
    public function handle(): Currency
    {
        $this->authorize();

        event(new CurrencyUpdating($this->model, $this->request));

        // Force the rate to be 1 for default currency
        if ($this->request->get('default_currency')) {
            $this->request['rate'] = '1';
        }

        \DB::transaction(function () {
            $this->model->update($this->request->all());

            // Update default currency setting
            if ($this->request->get('default_currency')) {
                setting()->set('default.currency', $this->request->get('code'));
                setting()->save();
            }
        });

        event(new CurrencyUpdated($this->model, $this->request));

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

        if ($this->model->code != $this->request->get('code')) {
            $message = trans('messages.warning.disable_code', ['name' => $this->model->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }

        if ($this->request->has('enabled') && ! $this->request->get('enabled')) {
            $message = trans('messages.warning.disable_code', ['name' => $this->model->name, 'text' => implode(', ', $relationships)]);

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
