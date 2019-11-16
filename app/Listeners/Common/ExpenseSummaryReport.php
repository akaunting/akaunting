<?php

namespace App\Listeners\Common;

use App\Abstracts\Reports\Listener;
use App\Events\Common\ReportFilterApplying;
use App\Events\Common\ReportFilterShowing;
use App\Events\Common\ReportGroupApplying;
use App\Events\Common\ReportGroupShowing;

class ExpenseSummaryReport extends Listener
{
    protected $class = 'App\Reports\ExpenseSummary';

    /**
     * Handle filter showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportFilterShowing(ReportFilterShowing $event)
    {
        if (!$this->checkClass($event)) {
            return;
        }

        $event->class->filters['years'] = $this->getYears();
        $event->class->filters['accounts'] = $this->getAccounts();
        $event->class->filters['categories'] = $this->getExpenseCategories();
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
        if (!$this->checkClass($event)) {
            return;
        }

        $event->class->groups['category'] = trans_choice('general.categories', 1);
        $event->class->groups['account'] = trans_choice('general.accounts', 1);
        $event->class->groups['vendor'] = trans_choice('general.vendors', 1);
    }

    /**
     * Handle group applyinh event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportGroupApplying(ReportGroupApplying $event)
    {
        if (!$this->checkClass($event)) {
            return;
        }

        $this->applyVendorGroup($event);
        $this->applyAccountGroup($event);
    }
}