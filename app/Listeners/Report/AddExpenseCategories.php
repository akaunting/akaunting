<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterShowing;
use App\Events\Report\GroupShowing;
use App\Events\Report\RowsShowing;

class AddExpenseCategories extends Listener
{
    protected $classes = [
        \App\Reports\ExpenseSummary::class,
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

        // send true for add limit on search and filter..
        $event->class->filters['categories'] = $this->getExpenseCategories(limit: true);
        $event->class->filters['routes']['categories'] = ['categories.index', 'search=type:' . $this->getExpenseCategoryTypes('string') . ' enabled:1'];
        $event->class->filters['multiple']['categories'] = true;
        $event->class->filters['operators']['categories'] = [
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

        $event->class->groups['category'] = trans_choice('general.categories', 1);
    }

    /**
     * Handle rows showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleRowsShowing(RowsShowing $event)
    {
        if ($this->skipRowsShowing($event, 'category')) {
            return;
        }

        $rows = $this->filterRowsBySearchString($this->getExpenseCategories(), 'category_id');

        $this->setRowNamesAndValues($event, $rows);

        $event->class->row_tree_nodes = [];

        $nodes = $this->getCategoriesNodes($rows);

        $this->setTreeNodes($event, $nodes);
    }
}
