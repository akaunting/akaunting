<?php

namespace App\Jobs\Setting;

use App\Abstracts\Job;
use App\Interfaces\Job\HasOwner;
use App\Interfaces\Job\HasSource;
use App\Interfaces\Job\ShouldCreate;
use App\Models\Setting\Currency;

class CreateCurrency extends Job implements HasOwner, HasSource, ShouldCreate
{
    public function handle(): Currency
    {
        // Force the rate to be 1 for default currency
        if ($this->request->get('default_currency')) {
            $this->request['rate'] = '1';
        }

        \DB::transaction(function () {
            $this->model = Currency::create($this->request->all());

            // Update default currency setting
            if ($this->request->get('default_currency')) {
                setting()->set('default.currency', $this->request->get('code'));
                setting()->save();
            }
        });

        return $this->model;
    }
}
