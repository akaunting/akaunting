<?php

namespace App\Abstracts\Listeners;

use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Setting\Category;
use App\Traits\Contacts;
use App\Traits\DateTime;
use App\Traits\SearchString;
use Date;

abstract class Report
{
    use Contacts, DateTime, SearchString;

    protected $classes = [];

    protected $events = [
        'App\Events\Report\FilterShowing',
        'App\Events\Report\FilterApplying',
        'App\Events\Report\GroupShowing',
        'App\Events\Report\GroupApplying',
        'App\Events\Report\RowsShowing',
    ];

    public function skipThisClass($event)
    {
        return (empty($event->class) || !in_array(get_class($event->class), $this->classes));
    }

    public function skipRowsShowing($event, $group)
    {
        return $this->skipThisClass($event)
                || empty($event->class->model->settings->group)
                || ($event->class->model->settings->group != $group);
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

    public function getAccounts($limit = false)
    {
        $model = Account::enabled()->orderBy('name');

        if ($limit !== false) {
            $model->take(setting('default.select_limit'));
        }

        return $model->pluck('name', 'id')->toArray();
    }

    public function getItemCategories($limit = false)
    {
        return $this->getCategories('item', $limit);
    }

    public function getIncomeCategories($limit = false)
    {
        return $this->getCategories('income', $limit);
    }

    public function getExpenseCategories($limit = false)
    {
        return $this->getCategories('expense', $limit);
    }

    public function getIncomeExpenseCategories($limit = false)
    {
        return $this->getCategories(['income', 'expense'], $limit);
    }

    public function getCategories($types, $limit = false)
    {
        $model = Category::type($types)->orderBy('name');

        if ($limit !== false) {
            $model->take(setting('default.select_limit'));
        }

        return $model->pluck('name', 'id')->toArray();
    }

    public function getCustomers($limit = false)
    {
        return $this->getContacts($this->getCustomerTypes(), $limit);
    }

    public function getVendors($limit = false)
    {
        return $this->getContacts($this->getVendorTypes(), $limit);
    }

    public function getContacts($types, $limit = false)
    {
        $model = Contact::type($types)->orderBy('name');

        if ($limit !== false) {
            $model->take(setting('default.select_limit'));
        }

        return $model->pluck('name', 'id')->toArray();
    }

    public function applyDateFilter($event)
    {
        $event->model->monthsOfYear($event->args['date_field']);
    }

    public function applySearchStringFilter($event)
    {
        $input = request('search');

        // Remove year as it's handled based on financial start
        $search_year = 'year:' . $this->getSearchStringValue('year', '', $input);
        $input = str_replace($search_year, '', $input);

        $event->model->usingSearchString($input);
    }

    public function applyAccountGroup($event)
    {
        if ($event->model->getTable() != 'documents') {
            return;
        }

        $filter = explode(',', $this->getSearchStringValue('account_id'));

        $event->model->account_id = 0;

        foreach ($event->model->transactions as $transaction) {
            if (!empty($filter) && !in_array($transaction->account_id, $filter)) {
                continue;
            }

            $event->model->account_id = $transaction->account_id;

            break;
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

    public function setRowNamesAndValues($event, $rows)
    {
        foreach ($event->class->dates as $date) {
            foreach ($event->class->tables as $table) {
                foreach ($rows as $id => $name) {
                    $event->class->row_names[$table][$id] = $name;
                    $event->class->row_values[$table][$id][$date] = 0;
                }
            }
        }
    }

    public function getFormattedDate($event, $date)
    {
        if (empty($event->class->model->settings->period)) {
            return $date->copy()->format('Y-m-d');
        }

        switch ($event->class->model->settings->period) {
            case 'yearly':
                $d = $date->copy()->format($this->getYearlyDateFormat());
                break;
            case 'quarterly':
                $start = $date->copy()->startOfQuarter()->format($this->getQuarterlyDateFormat());
                $end = $date->copy()->endOfQuarter()->format($this->getQuarterlyDateFormat());

                $d = $start . '-' . $end;
                break;
            default:
                $d = $date->copy()->format($this->getMonthlyDateFormat());
                break;
        }

        return $d;
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
