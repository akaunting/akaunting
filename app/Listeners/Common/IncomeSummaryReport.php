<?php

namespace App\Listeners\Common;

use App\Abstracts\Reports\Listener;
use App\Events\Common\ReportFilterApplying;
use App\Events\Common\ReportFilterShowing;
use App\Events\Common\ReportGroupApplying;
use App\Events\Common\ReportGroupShowing;

class IncomeSummaryReport extends Listener
{
    protected $class = 'App\Reports\IncomeSummary';

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
        $event->class->filters['categories'] = $this->getIncomeCategories();
        $event->class->filters['customers'] = $this->getCustomers();
    }

    /**
     * Handle filter applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportFilterApplying(ReportFilterApplying $event)
    {
        if (!$this->checkClass($event)) {
            return;
        }

        // Apply date
        $this->applyDateFilter($event);

        // Apply search
        $this->applySearchStringFilter($event);
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
        $event->class->groups['customer'] = trans_choice('general.customers', 1);
    }

    /**
     * Handle group applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportGroupApplying(ReportGroupApplying $event)
    {
        if (!$this->checkClass($event)) {
            return;
        }

        $this->applyCustomerGroup($event);
        $this->applyAccountGroup($event);
    }
}