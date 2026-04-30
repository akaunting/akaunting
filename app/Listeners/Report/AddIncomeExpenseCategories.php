<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterShowing;
use App\Events\Report\GroupShowing;
use App\Events\Report\RowsShowing;
use App\Models\Setting\Category;

class AddIncomeExpenseCategories extends Listener
{
    protected $classes = [
        \App\Reports\DiscountSummary::class,
        \App\Reports\IncomeExpenseSummary::class,
        \App\Reports\ProfitLoss::class,
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

        $types = array_merge($this->getIncomeCategoryTypes(), $this->getExpenseAndCogsCategoryTypes());

        $event->class->filters['categories'] = $this->getIncomeExpenseCategories(limit: true);
        $event->class->filters['routes']['categories'] = ['categories.index', 'search=type:' . implode(',', $types) . ' enabled:1'];
        $event->class->filters['multiple']['categories'] = true;
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
        if ($this->skipRowsShowing($event, 'category')) {
            return;
        }

        $types = array_merge($this->getIncomeCategoryTypes(), $this->getExpenseAndCogsCategoryTypes());
        $categories = Category::type($types)->orderBy('name')->get();
        $rows = $categories->pluck('name', 'id')->toArray();

        $this->setRowNamesAndValuesForCategories($event, $rows, $categories);

        $nodes = $this->getCategoriesNodes($rows);

        $this->setTreeNodesForCategories($event, $nodes, $categories);
    }

    public function setRowNamesAndValuesForCategories($event, $rows, $categories)
    {
        foreach ($event->class->dates as $date) {
            foreach ($event->class->tables as $table_key => $table_name) {
                $table_keys = $this->getCategoryTypesForTable($table_key);

                foreach ($rows as $id => $name) {
                    $category = $categories->where('id', $id)->first();

                    if (! in_array($category->type, $table_keys)) {
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
            $table_keys = $this->getCategoryTypesForTable($table_key);

            foreach ($nodes as $id => $node) {
                $category = $categories->where('id', $id)->first();

                if (! in_array($category->type, $table_keys)) {
                    continue;
                }

                $event->class->row_tree_nodes[$table_key][$id] = $node;
            }
        }
    }

    protected function getCategoryTypesForTable(string $table_key): array
    {
        return match ($table_key) {
            Category::INCOME_TYPE  => $this->getIncomeCategoryTypes(),
            Category::EXPENSE_TYPE => $this->getExpenseCategoryTypes(),
            Category::COGS_TYPE    => $this->getCogsCategoryTypes(),
            default                => $this->getExpenseCategoryTypes(),
        };
    }
}
