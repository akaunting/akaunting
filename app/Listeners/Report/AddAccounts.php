<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterShowing;
use App\Events\Report\GroupApplying;
use App\Events\Report\GroupShowing;
use App\Events\Report\RowsShowing;

class AddAccounts extends Listener
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
    public function handleFilterShowing(FilterShowing $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        // send true for add limit on search and filter..
        $event->class->filters['accounts'] = $this->getAccounts(true);
        $event->class->filters['routes']['accounts'] = ['accounts.index', 'search=enabled:1'];
        $event->class->filters['multiple']['accounts'] = true;
        $event->class->filters['operators']['accounts'] = [
            'equal'     => true,
            'not_equal' => true,
            'multiple'  => true,
            'range'     => false,
        ];
    }

    /**
     * Handle group showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleGroupShowing(GroupShowing $event)
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
    public function handleGroupApplying(GroupApplying $event)
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
    public function handleRowsShowing(RowsShowing $event)
    {
        if ($this->skipRowsShowing($event, 'account')) {
            return;
        }

        $rows = $this->filterRowsBySearchString($this->getAccounts(), 'account_id');

        $this->setRowNamesAndValues($event, $rows);
    }
}
