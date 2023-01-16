<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterApplying;
use App\Events\Report\FilterShowing;

class AddDate extends Listener
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
    public function handleFilterShowing(FilterShowing $event)
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
    public function handleFilterApplying(FilterApplying $event)
    {
        if (empty($event->args['date_field'])) {
            return;
        }

        // Apply date
        $this->applyDateFilter($event);
    }
}
