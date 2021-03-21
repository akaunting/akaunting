<?php

namespace App\Http\ViewComposers;

use Date;
use Illuminate\View\View;

class Show
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
            $y->subYear();
        }

        $view->with(['limits' => $limits, 'this_year' => $this_year, 'years' => $years]);
    }
}
