<?php

namespace App\Listeners\Update\V21;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\Company;
use App\Utilities\Overrider;

class Version213 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.1.3';

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

            $this->updateSettings();
        }

        company($company_id)->makeCurrent();
    }

    public function updateSettings()
    {
        $company_logo = setting('company.logo');

        if (is_array($company_logo)) {
            setting()->set('company.logo', $company_logo['id']);
        }

        setting()->save();
    }
}
