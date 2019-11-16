<?php

namespace App\Abstracts\Reports;

use App\Events\Common\ReportFilterApplying;
use App\Events\Common\ReportFilterShowing;
use App\Events\Common\ReportGroupApplying;
use App\Events\Common\ReportGroupShowing;
use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Setting\Category;
use App\Traits\Contacts;
use Date;

abstract class Listener
{
    use Contacts;

    protected $class = '';

    protected $events = [
        'App\Events\Common\ReportFilterShowing',
        'App\Events\Common\ReportFilterApplying',
        'App\Events\Common\ReportGroupShowing',
        'App\Events\Common\ReportGroupApplying',
    ];

    public function checkClass($event)
    {
        return (get_class($event->class) == $this->class);
    }

    public function getYears()
    {
        $now = Date::now();

        $years = [];

        $y = $now->addYears(2);
        for ($i = 0; $i < 10; $i++) {
            $years[$y->year] = $y->year;
            $y->subYear();
        }

        return $years;
    }

    public function getAccounts()
    {
        return Account::enabled()->orderBy('name')->pluck('name', 'id')->toArray();
    }

    public function getItemCategories()
    {
        return $this->getCategories('item');
    }

    public function getIncomeCategories()
    {
        return $this->getCategories('income');
    }

    public function getExpenseCategories()
    {
        return $this->getCategories('expense');
    }

    public function getIncomeExpenseCategories()
    {
        return $this->getCategories(['income', 'expense']);
    }

    public function getCategories($types)
    {
        return Category::type($types)->enabled()->orderBy('name')->pluck('name', 'id')->toArray();
    }

    public function getCustomers()
    {
        return $this->getContacts($this->getCustomerTypes());
    }

    public function getVendors()
    {
        return $this->getContacts($this->getVendorTypes());
    }

    public function getContacts($types)
    {
        return Contact::type($types)->enabled()->orderBy('name')->pluck('name', 'id')->toArray();
    }

    public function applyDateFilter($event)
    {
        $event->model->monthsOfYear($event->args['date_field']);
    }

    public function applySearchStringFilter($event)
    {
        $event->model->usingSearchString(request('search'));
    }

    public function applyAccountGroup($event)
    {
        if (($event->model->getTable() != 'invoices') && ($event->model->getTable() != 'bills')) {
            return;
        }

        $filter = request('accounts', []);

        $event->model->account_id = 0;

        foreach ($event->model->transactions as $transaction) {
            if (!empty($filter) && !in_array($transaction->account_id, $filter)) {
                continue;
            }

            $event->model->account_id = $transaction->account_id;
        }
    }

    public function applyCustomerGroup($event)
    {
        foreach ($this->getCustomerTypes() as $type) {
            $id_field = $type . '_id';

            $event->model->$id_field = $event->model->contact_id;
        }
    }

    public function applyVendorGroup($event)
    {
        foreach ($this->getVendorTypes() as $type) {
            $id_field = $type . '_id';

            $event->model->$id_field = $event->model->contact_id;
        }
    }

    /**
     * Handle filter showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportFilterShowing(ReportFilterShowing $event)
    {
        if (!$this->checkClass($event)) {
            return;
        }

        $event->class->filters['years'] = $this->getYears();
    }

    /**
     * Handle filter applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportFilterApplying(ReportFilterApplying $event)
    {
        if (!$this->checkClass($event)) {
            return;
        }

        // Apply date
        $this->applyDateFilter($event);

        // Apply search
        $this->applySearchStringFilter($event);
    }

    /**
     * Handle group showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportGroupShowing(ReportGroupShowing $event)
    {
        if (!$this->checkClass($event)) {
            return;
        }

        $event->class->groups['category'] = trans_choice('general.categories', 1);
    }

    /**
     * Handle group applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportGroupApplying(ReportGroupApplying $event)
    {
        if (!$this->checkClass($event)) {
            return;
        }

        $this->applyAccountGroup($event);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $class = get_class($this);

        foreach ($this->events as $event) {
            $method = 'handle' . (new \ReflectionClass($event))->getShortName();

            if (!method_exists($class, $method)) {
                continue;
            }

            $events->listen(
                $event,
                $class . '@' . $method
            );
        }
    }
}
