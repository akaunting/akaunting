<?php

namespace App\Console\Commands;

use App\Models\Common\Company;
use App\Models\Common\Report;
use App\Utilities\Reports as Utility;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ReportCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate and cache reports';

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

            $this->info('Calculating reports for ' . $company->name . ' company.');

            // Set company
            $company->makeCurrent();

            $this->cacheReportsOfCurrentCompany();
        }

        Company::forgetCurrent();
    }

    protected function cacheReportsOfCurrentCompany()
    {
        $reports = Report::orderBy('name')->get();

        foreach ($reports as $report) {
            try {
                $class = Utility::getClassInstance($report, false);

                if (empty($class)) {
                    continue;
                }

                $ttl = 3600 * 6; // 6 hours

                Cache::forget('reports.totals.' . $report->id);

                Cache::remember('reports.totals.' . $report->id, $ttl, function () use ($class) {
                    return $class->getGrandTotal();
                });
            } catch (\Throwable $e) {
                $this->error($e->getMessage());

                report($e);
            }
        }
    }
}
