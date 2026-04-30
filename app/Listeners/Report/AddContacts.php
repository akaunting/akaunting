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

        $all_contacts = $this->getContacts();

        if ($contact_ids = $this->getSearchStringValue('contact_id')) {
            $contacts = explode(',', $contact_ids);

            $rows = collect($all_contacts)->filter(function ($value, $key) use ($contacts) {
                return in_array($key, $contacts);
            });
        } else {
            $rows = $all_contacts;
        }

        $this->setRowNamesAndValues($event, $rows);
    }
}
