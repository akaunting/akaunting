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
        $company_id = session('company_id');

        $companies = Company::cursor();

        foreach ($companies as $company) {
            session(['company_id' => $company->id]);

            $this->updateSettings($company);
        }

        setting()->forgetAll();

        session(['company_id' => $company_id]);

        Overrider::load('settings');
    }

    public function updateSettings($company)
    {
        // Set the active company settings
        setting()->setExtraColumns(['company_id' => $company->id]);
        setting()->forgetAll();
        setting()->load(true);

        setting()->set(['invoice.payment_terms' => setting('invoice.payment_terms', 0)]);

        setting()->save();
    }
}
