<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterApplying;
use App\Events\Report\FilterShowing;

class AddDiscount extends Listener
{
    protected $classes = [
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

        $event->class->filters['discounts'] = $this->getDiscount();
        $event->class->filters['keys']['discounts'] = 'discount';
        $event->class->filters['defaults']['discounts'] = 'both';
        $event->class->filters['operators']['discounts'] = [
            'equal'     => true,
            'not_equal' => false,
            'range'     => false,
        ];
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
        
        // Apply discount filter
        $this->applyDiscountFilter($event);
    }
}
