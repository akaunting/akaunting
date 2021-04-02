<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterShowing;
use App\Events\Report\GroupShowing;
use App\Events\Report\RowsShowing;
use App\Models\Setting\Category;

class AddIncomeExpenseCategories extends Listener
{
    /**
     * Handle filter showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleFilterShowing(FilterShowing $event)
    {
        $classes = [
            'App\Reports\IncomeExpenseSummary',
        ];

        if (empty($event->class) || !in_array(get_class($event->class), $classes)) {
            return;
        }

        $event->class->filters['categories'] = $this->getIncomeExpenseCategories(true);
        $event->class->filters['routes']['categories'] = ['categories.index', 'search=type:income,expense'];
    }

    /**
     * Handle group showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleGroupShowing(GroupShowing $event)
    {
        $classes = [
            'App\Reports\IncomeExpenseSummary',
            'App\Reports\ProfitLoss',
        ];

        if (empty($event->class) || !in_array(get_class($event->class), $classes)) {
            return;
        }

        $event->class->groups['category'] = trans_choice('general.categories', 1);
    }

    /**
     * Handle records showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleRowsShowing(RowsShowing $event)
    {
        if (
            empty($event->class)
            || empty($event->class->model->settings->group)
            || ($event->class->model->settings->group != 'category')
        ) {
            return;
        }

        switch (get_class($event->class)) {
            case 'App\Reports\ProfitLoss':
                $categories = Category::type(['income', 'expense'])->orderBy('name')->get();
                $rows = $categories->pluck('name', 'id')->toArray();

                $this->setRowNamesAndValuesForProfitLoss($event, $rows, $categories);

                break;
            case 'App\Reports\IncomeExpenseSummary':
                $all_categories = $this->getIncomeExpenseCategories();

                if ($category_ids = $this->getSearchStringValue('category_id')) {
                    $categories = explode(',', $category_ids);

                    $rows = collect($all_categories)->filter(function ($value, $key) use ($categories) {
                        return in_array($key, $categories);
                    });
                } else {
                    $rows = $all_categories;
                }

                $this->setRowNamesAndValues($event, $rows);

                break;
        }
    }

    public function setRowNamesAndValuesForProfitLoss($event, $rows, $categories)
    {
        foreach ($event->class->dates as $date) {
            foreach ($event->class->tables as $type_id => $type_name) {
                foreach ($rows as $id => $name) {
                    $category = $categories->where('id', $id)->first();

                    if ($category->type != $type_id) {
                        continue;
                    }

                    $event->class->row_names[$type_name][$id] = $name;
                    $event->class->row_values[$type_name][$id][$date] = 0;
                }
            }
        }
    }
}
