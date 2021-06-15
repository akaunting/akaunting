<?php

namespace App\Console\Commands;

use App\Models\Common\Company;
use App\Models\Common\Report;
use App\Utilities\Reports as Utility;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ReportReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate reminders for reports';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Disable model cache
        config(['laravel-model-caching.enabled' => false]);

        // Get all companies
        $companies = Company::enabled()->withCount('reports')->cursor();

        foreach ($companies as $company) {
            // Has company reports
            if (!$company->reports_count) {
                continue;
            }

            $this->info('Calculate report reminders for ' . $company->name . ' company.');

            // Set company
            $company->makeCurrent();

            $this->remind();
        }

        Company::forgetCurrent();
    }

    protected function remind()
    {
        $reports = Report::orderBy('name')->get();

        foreach ($reports as $report) {
            $class = Utility::getClassInstance($report, false);

            if (empty($class)) {
                continue;
            }

            $ttl = 3600 * 6; // 6 hours

            Cache::remember('reports.totals.' . $report->id, $ttl, function () use ($class) {
                return $class->getGrandTotal();
            });
        }
    }
}
