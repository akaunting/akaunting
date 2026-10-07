<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterShowing;
use App\Events\Report\GroupApplying;
use App\Events\Report\GroupShowing;
use App\Events\Report\RowsShowing;

class AddVendors extends Listener
{
    protected $classes = [
        \App\Reports\ExpenseSummary::class,
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

        $event->class->filters['vendors'] = $this->getVendors(true);
        $event->class->filters['routes']['vendors'] = ['vendors.index', 'search=enabled:1'];
        $event->class->filters['multiple']['vendors'] = true;
        $event->class->filters['operators']['vendors'] = [
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

        $event->class->groups['vendor'] = trans_choice('general.vendors', 1);
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

        $this->applyVendorGroup($event);
    }

    /**
     * Handle rows showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleRowsShowing(RowsShowing $event)
    {
        if ($this->skipRowsShowing($event, 'vendor')) {
            return;
        }

        // The vendor chip sends contact_id, as every contact chip does
        $rows = $this->filterRowsBySearchString($this->getVendors(), 'contact_id');

        $this->setRowNamesAndValues($event, $rows);
    }
}
