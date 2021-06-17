<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Models\Setting\Currency;

class CreateCurrency extends Job
{
    protected $currency;

    protected $request;

    /**
     * Create a new job instance.
     *
     * @param  $request
     */
    public function __construct($request)
    {
        $this->request = $this->getRequestInstance($request);
        $this->request->merge(['created_by' => user_id()]);
    }

    /**
     * Execute the job.
     *
     * @return Currency
     */
    public function handle()
    {
        // Force the rate to be 1 for default currency
        if ($this->request->get('default_currency')) {
            $this->request['rate'] = '1';
        }

        \DB::transaction(function () {
            $this->currency = Currency::create($this->request->all());

            // Update default currency setting
            if ($this->request->get('default_currency')) {
                setting()->set('default.currency', $this->request->get('code'));
                setting()->save();
            }
        });

        return $this->currency;
    }
}
