<?php

namespace App\Listeners\Update\V21;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\Company;
use App\Models\Common\EmailTemplate;
use Illuminate\Support\Facades\Artisan;

class Version21199 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.1.19';

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
    }

    protected function updateEmailTemplate()
    {
        $company_id = company_id();

        $companies = Company::cursor();

        foreach ($companies as $company) {
            $company->makeCurrent();

            EmailTemplate::create([
                'company_id' => $company->id,
                'alias' => 'payment_new_vendor',
                'class' => 'App\Notifications\Purchase\Payment',
                'name' => 'settings.email.templates.payment_new_vendor',
                'subject' => trans('email_templates.payment_new_vendor.subject'),
                'body' => trans('email_templates.payment_new_vendor.body'),
            ]);
        }

        company($company_id)->makeCurrent();
    }
}
