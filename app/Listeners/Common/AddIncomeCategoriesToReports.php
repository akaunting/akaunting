<?php

namespace App\Listeners\Common;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Common\ReportFilterShowing;
use App\Events\Common\ReportGroupShowing;
use App\Events\Common\ReportRowsShowing;

class AddIncomeCategoriesToReports extends Listener
{
    protected $classes = [
        'App\Reports\IncomeSummary',
    ];

    /**
     * Handle filter showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportFilterShowing(ReportFilterShowing $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $event->class->filters['categories'] = $this->getIncomeCategories();
    }

    /**
     * Handle group showing event.
     *
     * @param  $event
     * @return void
     */
    public function handleReportGroupShowing(ReportGroupShowing $event)
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
    public function handleReportRowsShowing(ReportRowsShowing $event)
    {
        if ($this->skipRowsShowing($event, 'category')) {
            return;
        }

        if ($categories = request('categories')) {
            $rows = collect($event->class->filters['categories'])->filter(function ($value, $key) use ($categories) {
                return in_array($key, $categories);
            });
        } else {
            $rows = $event->class->filters['categories'];
        }

        $this->setRowNamesAndValues($event, $rows);
    }
}
