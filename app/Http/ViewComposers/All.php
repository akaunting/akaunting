<?php

namespace App\Http\ViewComposers;

use App\Traits\DateTime;
use Illuminate\View\View;

class All
{
    use DateTime;

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Make sure it's installed
        if (env('APP_INSTALLED')) {
            // Share date format
            $view->with(['date_format' => $this->getCompanyDateFormat()]);
        }
    }
}
