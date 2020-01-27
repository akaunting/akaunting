<?php

namespace App\Listeners\Common;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Common\ReportFilterShowing;
use App\Events\Common\ReportGroupApplying;
use App\Events\Common\ReportGroupShowing;
use App\Events\Common\ReportRowsShowing;

class AddCustomersToReports extends Listener
{
    protected $classes = [
        'App\Reports\IncomeSummary',
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

        $event->class->filters['customers'] = $this->getCustomers();
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
        if ($this->skipThisClass($event)) {
            return;
        }

        $this->applyCustomerGroup($event);
    }

    /**
     * Handle rows showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportRowsShowing(ReportRowsShowing $event)
    {
        if ($this->skipRowsShowing($event, 'customer')) {
            return;
        }

        if ($customers = request('customers')) {
            $rows = collect($event->class->filters['customers'])->filter(function ($value, $key) use ($customers) {
                return in_array($key, $customers);
            });
        } else {
            $rows = $event->class->filters['customers'];
        }

        $this->setRowNamesAndValues($event, $rows);
    }
}
