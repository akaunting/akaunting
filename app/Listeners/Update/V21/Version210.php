<?php

namespace App\Listeners\Update\V21;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Setting\Category;
use App\Models\Common\Company;
use App\Utilities\Overrider;
use Illuminate\Support\Facades\Artisan;

class Version210 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.1.0';

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

        Artisan::call('migrate', ['--force' => true]);
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
        $sales_category = Category::income()->enabled()->first();
        $purchases_category = Category::expense()->enabled()->first();

        // Set the active company settings
        setting()->setExtraColumns(['company_id' => $company->id]);
        setting()->forgetAll();
        setting()->load(true);

        setting()->set(['default.sales_category' => setting('default.sales_category', $sales_category->id)]);
        setting()->set(['default.purchases_category' => setting('default.purchases_category', $purchases_category->id)]);

        setting()->save();
    }
}
