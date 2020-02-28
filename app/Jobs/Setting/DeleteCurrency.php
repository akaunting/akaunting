<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;

class DeleteCurrency extends Job
{
    protected $currency;

    /**
     * Create a new job instance.
     *
     * @param  $currency
     */
    public function __construct($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Execute the job.
     *
     * @return boolean|Exception
     */
    public function handle()
    {
        $this->authorize();

        $this->currency->delete();

        return true;
    }

    /**
     * Determine if this action is applicable.
     *
     * @return void
     */
    public function authorize()
    {
        if ($relationships = $this->getRelationships()) {
            $message = trans('messages.warning.deleted', ['name' => $this->currency->name, 'text' => implode(', ', $relationships)]);

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
