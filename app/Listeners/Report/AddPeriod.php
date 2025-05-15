<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterShowing;

class AddPeriod extends Listener
{
    protected $classes = [
        'App\Reports\IncomeSummary',
        'App\Reports\ExpenseSummary',
        'App\Reports\IncomeExpenseSummary',
        'App\Reports\ProfitLoss',
        'App\Reports\TaxSummary',
        'App\Reports\DiscountSummary',
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

        $event->class->filters['period'] = $this->getPeriod();
        $event->class->filters['keys']['period'] = 'period';
        $event->class->filters['defaults']['period'] = $event->class->getSetting('period', 'quarterly');
        $event->class->filters['operators']['period'] = [
            'equal'     => true,
            'not_equal' => false,
            'range'     => false,
        ];
    }
}
