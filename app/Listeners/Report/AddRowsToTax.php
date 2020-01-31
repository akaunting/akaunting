<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\RowsShowing;

class AddRowsToTax extends Listener
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
    public function handleRowsShowing(RowsShowing $event)
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
