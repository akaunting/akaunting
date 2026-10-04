<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterApplying;
use App\Events\Report\FilterShowing;
use App\Events\Report\GroupApplying;
use App\Events\Report\GroupShowing;
use App\Events\Report\RowsShowing;

class AddCustomers extends Listener
{
    protected $classes = [
        \App\Reports\IncomeSummary::class,
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
        $event->class->filters['routes']['customers'] = ['customers.index', 'search=enabled:1'];
        $event->class->filters['multiple']['customers'] = true;
        $event->class->filters['operators']['customers'] = [
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
     * Handle filter applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleFilterApplying(FilterApplying $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $model_type = $event->args['model_type'] ?? 'invoice';
        if (! in_array($model_type, ['invoice', 'income'])) {
            return;
        }

        if ($customer_ids = $this->getSearchStringValue('customer_id')) {
            $where = $this->getSearchStringOperator('customer_id') == '!='
                ? 'whereNotIn'
                : 'whereIn';

            $event->model->{$where}('contact_id', array_map('intval', explode(',', $customer_ids)));
        }
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

        // The customer chip sends contact_id, as every contact chip does
        $rows = $this->filterRowsBySearchString($this->getCustomers(), 'contact_id');

        $this->setRowNamesAndValues($event, $rows);
    }
}
