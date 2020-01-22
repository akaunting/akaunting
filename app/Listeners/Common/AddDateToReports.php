<?php

namespace App\Listeners\Common;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Common\ReportFilterApplying;
use App\Events\Common\ReportFilterShowing;

class AddDateToReports extends Listener
{
    protected $classes = [
        'App\Reports\IncomeSummary',
        'App\Reports\ExpenseSummary',
        'App\Reports\IncomeExpenseSummary',
        'App\Reports\ProfitLoss',
        'App\Reports\TaxSummary',
    ];

    /**
     * Handle filter showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportFilterShowing(ReportFilterShowing $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $event->class->filters['years'] = $this->getYears();
    }

    /**
     * Handle filter applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportFilterApplying(ReportFilterApplying $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        // Apply date
        $this->applyDateFilter($event);
    }
}
