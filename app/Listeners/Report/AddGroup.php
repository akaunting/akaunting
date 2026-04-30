<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterShowing;

class AddGroup extends Listener
{
    protected $classes = [
        \App\Reports\ExpenseSummary::class,
        \App\Reports\IncomeExpenseSummary::class,
        \App\Reports\IncomeSummary::class,
        \App\Reports\ProfitLoss::class,
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

        $event->class->filters['group'] = $event->class->groups;
        $event->class->filters['keys']['group'] = 'group';
        $event->class->filters['names']['group'] = trans('general.group_by');
        $event->class->filters['defaults']['group'] = $event->class->getGroup();
        $event->class->filters['operators']['group'] = [
            'equal'     => true,
            'not_equal' => false,
            'range'     => false,
        ];
    }
}
