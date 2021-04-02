<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;

class Menu
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        // Get user companies
        if ($user = user()) {
            $companies = $user->companies()->enabled()->limit(10)->get()->sortBy('name');
        } else {
            $companies = [];
        }

        $view->with(['companies' => $companies]);
    }
}
