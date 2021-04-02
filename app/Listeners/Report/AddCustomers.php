<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterShowing;
use App\Events\Report\GroupApplying;
use App\Events\Report\GroupShowing;
use App\Events\Report\RowsShowing;

class AddCustomers extends Listener
{
    protected $classes = [
        'App\Reports\IncomeSummary',
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

        $event->class->filters['customers'] = $this->getCustomers(true);
        $event->class->filters['routes']['customers'] = 'customers.index';
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

        $event->class->groups['customer'] = trans_choice('general.customers', 1);
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

        $this->applyCustomerGroup($event);
    }

    /**
     * Handle rows showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleRowsShowing(RowsShowing $event)
    {
        if ($this->skipRowsShowing($event, 'customer')) {
            return;
        }

        $all_customers = $this->getCustomers();

        if ($customer_ids = $this->getSearchStringValue('customer_id')) {
            $customers = explode(',', $customer_ids);

            $rows = collect($all_customers)->filter(function ($value, $key) use ($customers) {
                return in_array($key, $customers);
            });
        } else {
            $rows = $all_customers;
        }

        $this->setRowNamesAndValues($event, $rows);
    }
}
