<?php

namespace App\Listeners\Common;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Common\ReportFilterShowing;
use App\Events\Common\ReportGroupShowing;

class AddExpenseCategoriesToReports extends Listener
{
    /**
     * Handle filter showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportFilterShowing(ReportFilterShowing $event)
    {
        $classes = [
            'App\Reports\ExpenseSummary',
            'App\Reports\IncomeExpenseSummary',
        ];

        if (empty($event->class) || !in_array(get_class($event->class), $classes)) {
            return;
        }

        $categories = !empty($event->class->filters['categories']) ? $event->class->filters['categories'] : [];

        $event->class->filters['categories'] = array_merge($categories, $this->getExpenseCategories());
    }

    /**
     * Handle group showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportGroupShowing(ReportGroupShowing $event)
    {
        $classes = [
            'App\Reports\ExpenseSummary',
            'App\Reports\IncomeExpenseSummary',
            'App\Reports\ProfitLoss',
            'App\Reports\TaxSummary',
        ];

        if (empty($event->class) || !in_array(get_class($event->class), $classes)) {
            return;
        }

        $event->class->groups['category'] = trans_choice('general.categories', 1);
    }
}
