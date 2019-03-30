<?php

namespace App\Http\ViewComposers;

use Date;
use Illuminate\View\View;

class Index
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $limits = ['10' => '10', '25' => '25', '50' => '50', '100' => '100'];

        $now = Date::now();

        $this_year = $now->year;

        $years = [];
        $y = $now->addYears(2);

        for ($i = 0; $i < 10; $i++) {
            $years[$y->year] = $y->year;
            
            if (Date::parse(setting('general.financial_start'))->month != 1) {
                $nY = $y->year + 1;
                // format the dropdown text to indicate that the next
                // calendar year is part of financial year
                $years[$y->year] = "{$y->year}-{$nY}";
            }

            $y->subYear();
        }

        $view->with(['limits' => $limits, 'this_year' => $this_year, 'years' => $years]);
    }
}
