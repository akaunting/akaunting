<?php

namespace App\Http\ViewComposers;

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
        
        $years = ['2017' => '2017', '2016' => '2016', '2015' => '2015', '2014' => '2014'];

        $view->with(['limits' => $limits, 'years' => $years]);
    }
}
