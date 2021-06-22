<?php

namespace App\Listeners\Update\V21;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\Company;
use App\Models\Common\Report;
use App\Utilities\Reports as Utility;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class Version2117 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.1.17';

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

        $this->updateCompanies();

        Artisan::call('migrate', ['--force' => true]);
    }

    protected function updateCompanies()
    {
        $company_id = company_id();

        $companies = Company::cursor();

        foreach ($companies as $company) {
            $company->makeCurrent();

            $this->cacheReports();
        }

        company($company_id)->makeCurrent();
    }

    protected function cacheReports()
    {
        try {
            Report::all()->each(function ($report) {
                Cache::put('reports.totals.' . $report->id, Utility::getClassInstance($report)->getGrandTotal());
            });
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
