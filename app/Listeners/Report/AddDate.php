<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterApplying;
use App\Events\Report\FilterShowing;

class AddDate extends Listener
{
    protected $classes = [
        \App\Reports\IncomeSummary::class,
        \App\Reports\ExpenseSummary::class,
        \App\Reports\IncomeExpenseSummary::class,
        \App\Reports\ProfitLoss::class,
        \App\Reports\TaxSummary::class,
        \App\Reports\DiscountSummary::class,
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

        $this->setDateFilter($event);
    }

    /**
     * Handle filter applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleFilterApplying(FilterApplying $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        if (empty($event->args['date_field'])) {
            return;
        }

        // Apply date
        $this->applyDateFilter($event);
    }
}
