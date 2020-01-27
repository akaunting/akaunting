<?php

namespace App\Listeners\Common;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Common\ReportRowsShowing;

class AddRowsToTaxReport extends Listener
{
    protected $classes = [
        'App\Reports\TaxSummary',
    ];

    /**
     * Handle rows showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportRowsShowing(ReportRowsShowing $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $rows = [
            'income' => trans_choice('general.sales', 2),
            'expense' => trans_choice('general.purchases', 2),
        ];

        $this->setRowNamesAndValues($event, $rows);
    }
}
