<?php

namespace App\Listeners\Report;

use App\Abstracts\Listeners\Report as Listener;
use App\Events\Report\FilterApplying;

class AddSearchString extends Listener
{
    protected $classes = [
        'App\Reports\IncomeSummary',
        'App\Reports\ExpenseSummary',
        'App\Reports\IncomeExpenseSummary',
        'App\Reports\ProfitLoss',
        'App\Reports\TaxSummary',
    ];

    /**
     * Handle filter applying event.
     *
     * @param  $event
     * @return void
     */
    public function handleFilterApplying(FilterApplying $event)
    {
        if ($this->skipThisClass($event)) {
            return;
        }

        $old = old();
        $request = request()->all();

        if ($old || $request) {
            $input = request('search');

            $filters = [];

            if ($input) {
                $filters = explode(' ', $input);
            }

            foreach ($old as $key => $value) {
                $filters[] = $key . ':' . $value;
            }

            foreach ($request as $key => $value) {
                if ($key == 'search') {
                    continue;
                }

                $filters[] = $key . ':' . $value;
            }

            request()->merge([
                'search' => implode(' ', $filters)
            ]);
        }

        // Apply search string
        $this->applySearchStringFilter($event);
    }
}
