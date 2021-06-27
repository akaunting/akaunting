<?php

namespace App\Listeners\Update\V21;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\Company;
use App\Models\Common\EmailTemplate;
use Illuminate\Support\Facades\Artisan;

class Version2118 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.1.18';

    /**
     * Handle the event.
     *
     * @param  $event
     *
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateEmailTemplate();

        Artisan::call('cache:clear');

        Artisan::call('migrate', ['--force' => true]);
    }

    protected function updateEmailTemplate()
    {
        $company_id = company_id();

        $companies = Company::cursor();

        foreach ($companies as $company) {
            $company->makeCurrent();

            EmailTemplate::create([
                'company_id' => $company->id,
                'alias' => 'revenue_new_customer',
                'class' => 'App\Notifications\Sale\Revenue',
                'name' => 'settings.email.templates.revenue_new_customer',
                'subject' => trans('email_templates.revenue_new_customer.subject'),
                'body' => trans('email_templates.revenue_new_customer.body'),
            ]);
        }

        company($company_id)->makeCurrent();
    }
}
