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

        $event->class->filters['vendors'] = $this->getVendors(true);
        $event->class->filters['routes']['vendors'] = 'vendors.index';
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

        $all_vendors = $this->getVendors();

        if ($vendor_ids = $this->getSearchStringValue('vendor_id')) {
            $vendors = explode(',', $vendor_ids);

            $rows = collect($all_vendors)->filter(function ($value, $key) use ($vendors) {
                return in_array($key, $vendors);
            });
        } else {
            $rows = $all_vendors;
        }

        $this->setRowNamesAndValues($event, $rows);
    }
}
