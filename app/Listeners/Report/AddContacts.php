<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterShowing;
use App\Events\Report\GroupShowing;
use App\Events\Report\RowsShowing;

class AddContacts extends Listener
{
    protected $classes = [
        \App\Reports\DiscountSummary::class,
        \App\Reports\IncomeExpenseSummary::class,
        \App\Reports\ProfitLoss::class,
        \App\Reports\TaxSummary::class,
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

        $event->class->filters['contacts'] = $this->getContacts(limit: true);
        $event->class->filters['routes']['contacts'] = ['contacts.index', 'search=enabled:1'];
        $event->class->filters['multiple']['contacts'] = true;
        $event->class->filters['operators']['contacts'] = [
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

        $event->class->groups['contact'] = trans_choice('general.contacts', 1);
    }

    /**
     * Handle rows showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleRowsShowing(RowsShowing $event)
    {
        if ($this->skipRowsShowing($event, 'contact')) {
            return;
        }

        $rows = $this->filterRowsBySearchString($this->getContacts(), 'contact_id');

        $this->setRowNamesAndValues($event, $rows);
    }
}
