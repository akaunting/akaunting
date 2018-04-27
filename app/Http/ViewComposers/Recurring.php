<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class Recurring
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $recurring_frequencies = [
            'no' => trans('general.no'),
            'daily' => trans('recurring.daily'),
            'weekly' => trans('recurring.weekly'),
            'monthly' => trans('recurring.monthly'),
            'yearly' => trans('recurring.yearly'),
            'custom' => trans('recurring.custom'),
        ];

        $recurring_custom_frequencies = [
            'daily' => trans('recurring.days'),
            'weekly' => trans('recurring.weeks'),
            'monthly' => trans('recurring.months'),
            'yearly' => trans('recurring.years'),
        ];

        $view->with(['recurring_frequencies' => $recurring_frequencies, 'recurring_custom_frequencies' => $recurring_custom_frequencies]);
    }
}
