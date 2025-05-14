<?php

namespace App\Abstracts\Listeners;

use App\Models\Banking\Account;
use App\Models\Common\Contact;
use App\Models\Setting\Category;
use App\Traits\Contacts;
use App\Traits\DateTime;
use App\Traits\SearchString;

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
        $fire_event = $event;

        $this->fireEvent('App\Events\Report\SkipClass', $fire_event);

        return (empty($event->class) || !in_array(get_class($event->class), $this->classes));
    }

    public function skipRowsShowing($event, $group)
    {
        $fire_event = $event;
        $fire_group = $group;

        $this->fireEvent('App\Events\Report\SkipRowsShowing', $fire_event, $fire_group);

        return $this->skipThisClass($event)
                || empty($event->class->model->settings->group)
                || ($event->class->model->settings->group != $group);
    }

    public function setDateFilter($event)
    {
        $financial_year = $this->getFinancialYear();

        $start_date = request()->get('start_date', $financial_year->copy()->getStartDate()->toDateString());
        $end_date = request()->get('end_date', $financial_year->copy()->getEndDate()->toDateString());
        $default_value = $start_date . '-to-' . $end_date;

        $event->class->filters['date_range'] = $this->getDateRange();
        $event->class->filters['keys']['date_range'] = 'date_range';
        $event->class->filters['defaults']['date_range'] = $default_value;
        $event->class->filters['operators']['date_range'] = [
            'equal'     => true,
            'not_equal' => false,
            'range'     => false,
        ];
    }

    public function getDateRange()
    {
        $date_range = [];

        $shortcuts = $this->getDatePickerShortcuts();

        foreach ($shortcuts as $text => $shortcut) {
            $date_range[$shortcut['start'] . '-to-' . $shortcut['end']] = $text;
        }

        $date_range['custom'] = trans('general.date_range.custom');

        return $date_range;
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
        $model = Category::withSubCategory()->type($types)->orderBy('name');

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

    public function getBasis()
    {
        return [
            'cash' => trans('general.cash'),
            'accrual' => trans('general.accrual'),
        ];
    }

    public function getPeriod()
    {
        return [
            'weekly' => trans('general.weekly'),
            'monthly' => trans('general.monthly'),
            'quarterly' => trans('general.quarterly'),
            'yearly' => trans('general.yearly'),
        ];
    }

    public function getDiscount()
    {
        return [
            'item' => trans('settings.localisation.discount_location.item'),
            'total' => trans('settings.localisation.discount_location.total'),
            'both' => trans('settings.localisation.discount_location.both'),
        ];
    }

    public function applyDateFilter($event)
    {
        $event->model->dateFilter($event->args['date_field']);
    }

    public function applyDiscountFilter($event)
    {
        $input = request('search', '');
        $discount = $this->getSearchStringValue('discount', 'both', $input);

        switch ($discount) {
            case 'item':
                $discount_types = ['item_discount'];
                break;
            case 'total':
                $discount_types = ['discount'];
                break;
            default:
                $discount_types = ['item_discount', 'discount'];
                break;
        }

        $event->model->whereHas('totals', function ($query) use ($discount_types) {
            $query->whereIn('code', $discount_types);
        });
    }

    public function applySearchStringFilter($event)
    {
        $input = request('search', '');

        // Remove basis as it's handled based on report itself
        $search_basis = 'basis:' . $this->getSearchStringValue('basis', 'accrual', $input);
        $input = str_replace($search_basis, '', $input);

        // Remove period as it's handled based on report itself
        $search_period = 'period:' . $this->getSearchStringValue('period', 'quarterly', $input);
        $input = str_replace($search_period, '', $input);

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
        $nodes = [];

        foreach ($event->class->dates as $date) {
            foreach ($event->class->tables as $table_key => $table_name) {
                foreach ($rows as $id => $name) {
                    $event->class->row_names[$table_key][$id] = $name;
                    $event->class->row_values[$table_key][$id][$date] = 0;

                    $nodes[$id] = null;
                }
            }
        }

        $this->setTreeNodes($event, $nodes);
    }

    public function setTreeNodes($event, $nodes)
    {
        foreach ($event->class->tables as $table_key => $table_name) {
            foreach ($nodes as $id => $node) {
                $event->class->row_tree_nodes[$table_key][$id] = $node;
            }
        }
    }

    public function getCategoriesNodes($categories)
    {
        $nodes = [];

        foreach ($categories as $id => $name) {
            $category = Category::withSubCategory()->find($id);

            if (!is_null($category->parent_id)) {
                unset($categories[$id]);

                continue;
            }

            $nodes[$id] = $this->getSubCategories($category);
        }

        return $nodes;
    }

    public function getSubCategories($category)
    {
        if ($category->sub_categories->count() == 0) {
            return null;
        }

        $sub_categories = [];

        foreach ($category->sub_categories as $sub_category) {
            $sub_category->load('sub_categories');

            $sub_categories[$sub_category->id] = $this->getSubCategories($sub_category);
        }

        if (!empty($sub_categories)) {
            $sub_categories[$category->id] = null;
        }

        return $sub_categories;
    }

    public function getFormattedDate($event, $date)
    {
        $period = $this->getSearchStringValue('period');

        if (empty($period)) {
            $period = $event->class->getSetting('period');
        }

        return $this->getPeriodicDate($date, $period, $event->class->year);
    }

    protected function fireEvent($event_class, $event, $group = null)
    {
        $this->class = $event->class;

        if ($group) {
            $this->group = $group;
        }

        event(new $event_class($this));
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
