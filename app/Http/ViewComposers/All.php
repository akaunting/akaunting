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
        if (!env('APP_INSTALLED') && (env('APP_ENV') !== 'testing')) {
            return;
        }

        // Share user logged in
        $auth_user = auth()->user();

        // Share date format
        $date_format = $auth_user ? $this->getCompanyDateFormat() : 'd F Y';

        $view->with(['auth_user' => $auth_user, 'date_format' => $date_format]);
    }
}
