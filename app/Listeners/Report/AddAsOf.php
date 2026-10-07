<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterShowing;
use App\Utilities\Date;

class AddAsOf extends Listener
{
    protected $classes = [];

    public function handleFilterShowing(FilterShowing $event): void
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $presets = $this->getAsOfPresets(Date::today());

        // The chip's values, one per date, and the applied date labelled with itself, so a date from a saved or
        // shared link is kept; the bar lists every preset, even two that share a date
        $values = [];

        foreach ($presets as $preset) {
            $values[$preset['date']] ??= $preset['label'];
        }

        $as_of = $event->class->getAsOfDate()->toDateString();
        $values[$as_of] ??= company_date($as_of);

        $event->class->filters['as_of'] = $values;
        $event->class->filters['presets']['as_of'] = $presets;
        $event->class->filters['keys']['as_of'] = 'as_of';
        $event->class->filters['names']['as_of'] = trans('reports.as_of');
        $event->class->filters['defaults']['as_of'] = $as_of;
        $event->class->filters['operators']['as_of'] = [
            'equal'     => true,
            'not_equal' => false,
            'multiple'  => false,
            'range'     => false,
        ];
    }

    /**
     * The presets in order, [date, label]: today, then the ends of this and last month, financial quarter and
     * financial year, the quarters counted from the start of the financial year that holds today as the period
     * columns count them. Two may share a date, as the end of last month and of last financial quarter do in a
     * quarter's first month.
     */
    public function getAsOfPresets(Date $today): array
    {
        $year_start = $this->getFinancialStartOf($today);

        // The last quarter of the year to start by today holds it
        $quarter = collect(range(0, 3))->last(fn (int $quarter) => $year_start->copy()->addQuarters($quarter)->lessThanOrEqualTo($today));

        return [
            [
                'date' => $today->toDateString(),
                'label' => trans('general.date_range.today'),
            ],
            [
                'date' => $today->copy()->endOfMonth()->toDateString(),
                'label' => trans('reports.as_of_presets.end_of_this_month'),
            ],
            [
                'date' => $today->copy()->startOfMonth()->subDay()->toDateString(),
                'label' => trans('reports.as_of_presets.end_of_last_month'),
            ],
            [
                'date' => $year_start->copy()->addQuarters($quarter + 1)->subDay()->toDateString(),
                'label' => trans('reports.as_of_presets.end_of_this_financial_quarter'),
            ],
            [
                'date' => $year_start->copy()->addQuarters($quarter)->subDay()->toDateString(),
                'label' => trans('reports.as_of_presets.end_of_last_financial_quarter'),
            ],
            [
                'date' => $year_start->copy()->addYear()->subDay()->toDateString(),
                'label' => trans('reports.as_of_presets.end_of_this_financial_year'),
            ],
            [
                'date' => $year_start->copy()->subDay()->toDateString(),
                'label' => trans('reports.as_of_presets.end_of_last_financial_year'),
            ],
        ];
    }
}
