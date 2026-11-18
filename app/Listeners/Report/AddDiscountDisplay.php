<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\RowsShowing;
use App\Models\Setting\Category;

/**
 * The discount row of the reports whose discount display setting (App\Traits\ReportDiscountDisplay) shows the
 * discount on the document total on a row of its own.
 */
class AddDiscountDisplay extends Listener
{
    protected $classes = [
        \App\Reports\IncomeSummary::class,
        \App\Reports\ExpenseSummary::class,
        \App\Reports\IncomeExpenseSummary::class,
        \App\Reports\ProfitLoss::class,
    ];

    /**
     * Handle rows showing event.
     *
     * The discount row, after the categories. A categories filter picks categories, which the discount is none
     * of, so the row is left out while one is applied, as an account filter leaves out the other accounts.
     *
     * @param  $event
     * @return void
     */
    public function handleRowsShowing(RowsShowing $event)
    {
        if ($this->skipThisClass($event) || ! $event->class->showsDiscountRow()) {
            return;
        }

        if ($event->class->getSearchStringValue('category_id') !== '') {
            return;
        }

        $row = $event->class::DISCOUNT_ROW;

        foreach ($event->class->tables as $table_key => $table_name) {
            $event->class->row_names[$table_key][$row] = ($table_key === Category::INCOME_TYPE)
                ? trans('reports.discount_display.sales')
                : trans('reports.discount_display.purchases');

            $event->class->row_tree_nodes[$table_key][$row] = null;

            foreach ($event->class->dates as $date) {
                $event->class->row_values[$table_key][$row][$date] = 0;
            }
        }
    }
}
