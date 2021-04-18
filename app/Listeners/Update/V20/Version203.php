<?php

namespace App\Listeners\Update\V20;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\Company;
use App\Utilities\Overrider;

class Version203 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.0.3';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateCompanies();
    }

    protected function updateCompanies()
    {
        $company_id = company_id();

        $companies = Company::cursor();

        foreach ($companies as $company) {
            $company->makeCurrent();

            $this->updateSettings($company);
        }

        company($company_id)->makeCurrent();
    }

    public function updateSettings($company)
    {
        setting()->set(['invoice.payment_terms' => setting('invoice.payment_terms', 0)]);

        setting()->save();
    }
}
