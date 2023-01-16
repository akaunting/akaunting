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
        $event->class->filters['routes']['categories'] = ['categories.index', 'search=type:income,expense enabled:1'];
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

        $categories = Category::type(['income', 'expense'])->orderBy('name')->get();
        $rows = $categories->pluck('name', 'id')->toArray();

        $this->setRowNamesAndValuesForCategories($event, $rows, $categories);

        $nodes = $this->getCategoriesNodes($rows);

        $this->setTreeNodesForCategories($event, $nodes, $categories);
    }

    public function setRowNamesAndValuesForCategories($event, $rows, $categories)
    {
        foreach ($event->class->dates as $date) {
            foreach ($event->class->tables as $table_key => $table_name) {
                foreach ($rows as $id => $name) {
                    $category = $categories->where('id', $id)->first();

                    if ($category->type != $table_key) {
                        continue;
                    }

                    $event->class->row_names[$table_key][$id] = $name;
                    $event->class->row_values[$table_key][$id][$date] = 0;
                }
            }
        }
    }

    public function setTreeNodesForCategories($event, $nodes, $categories)
    {
        foreach ($event->class->tables as $table_key => $table_name) {
            foreach ($nodes as $id => $node) {
                $category = $categories->where('id', $id)->first();

                if ($category->type != $table_key) {
                    continue;
                }

                $event->class->row_tree_nodes[$table_key][$id] = $node;
            }
        }
    }
}
