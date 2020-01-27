<?php

namespace App\Listeners\Common;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Common\ReportFilterShowing;
use App\Events\Common\ReportGroupApplying;
use App\Events\Common\ReportGroupShowing;
use App\Events\Common\ReportRowsShowing;

class AddVendorsToReports extends Listener
{
    protected $classes = [
        'App\Reports\ExpenseSummary',
        'App\Reports\IncomeExpenseSummary',
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

        $event->class->filters['vendors'] = $this->getVendors();
    }

    /**
     * Handle group showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportGroupShowing(ReportGroupShowing $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $event->class->groups['vendor'] = trans_choice('general.vendors', 1);
    }

    /**
     * Handle group applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportGroupApplying(ReportGroupApplying $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $this->applyVendorGroup($event);
    }

    /**
     * Handle rows showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportRowsShowing(ReportRowsShowing $event)
    {
        if ($this->skipRowsShowing($event, 'vendor')) {
            return;
        }

        if ($vendors = request('vendors')) {
            $rows = collect($event->class->filters['vendors'])->filter(function ($value, $key) use ($vendors) {
                return in_array($key, $vendors);
            });
        } else {
            $rows = $event->class->filters['vendors'];
        }

        $this->setRowNamesAndValues($event, $rows);
    }
}
