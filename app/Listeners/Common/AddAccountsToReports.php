<?php

namespace App\Listeners\Common;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Common\ReportFilterShowing;
use App\Events\Common\ReportGroupApplying;
use App\Events\Common\ReportGroupShowing;
use App\Events\Common\ReportRowsShowing;

class AddAccountsToReports extends Listener
{
    protected $classes = [
        'App\Reports\IncomeSummary',
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

        $event->class->filters['accounts'] = $this->getAccounts();
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

        $event->class->groups['account'] = trans_choice('general.accounts', 1);
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

        $this->applyAccountGroup($event);
    }

    /**
     * Handle rows showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportRowsShowing(ReportRowsShowing $event)
    {
        if ($this->skipRowsShowing($event, 'account')) {
            return;
        }

        if ($accounts = request('accounts')) {
            $rows = collect($event->class->filters['accounts'])->filter(function ($value, $key) use ($accounts) {
                return in_array($key, $accounts);
            });
        } else {
            $rows = $event->class->filters['accounts'];
        }

        $this->setRowNamesAndValues($event, $rows);
    }
}
