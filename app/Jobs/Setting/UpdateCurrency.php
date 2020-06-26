<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Models\Setting\Currency;

class UpdateCurrency extends Job
{
    protected $currency;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $currency
     * @param  $request
     */
    public function __construct($currency, $request)
    {
        $this->currency = $currency;
        $this->request = $this->getRequestInstance($request);
    }

    /**
     * Execute the job.
     *
     * @return Currency
     */
    public function handle()
    {
        $this->authorize();

        // Force the rate to be 1 for default currency
        if ($this->request->get('default_currency')) {
            $this->request['rate'] = '1';
        }

        \DB::transaction(function () {
            $this->currency->update($this->request->all());

            // Update default currency setting
            if ($this->request->get('default_currency')) {
                setting()->set('default.currency', $this->request->get('code'));
                setting()->save();
            }
        });

        return $this->currency;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if (!$relationships = $this->getRelationships()) {
            return;
        }

        if ($this->currency->code != $this->request->get('code')) {
            $message = trans('messages.warning.disable_code', ['name' => $this->currency->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }

        if (!$this->request->get('enabled')) {
            $message = trans('messages.warning.disable_code', ['name' => $this->currency->name, 'text' => implode(', ', $relationships)]);

            throw new \Exception($message);
        }
    }

    public function getRelationships()
    {
        $rels = [
            'accounts' => 'accounts',
            'customers' => 'customers',
            'invoices' => 'invoices',
            'bills' => 'bills',
            'transactions' => 'transactions',
        ];

        $relationships = $this->countRelationships($this->currency, $rels);

        if ($this->currency->code == setting('default.currency')) {
            $relationships[] = strtolower(trans_choice('general.companies', 1));
        }

        return $relationships;
    }
}
