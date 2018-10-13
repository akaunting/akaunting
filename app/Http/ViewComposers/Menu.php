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
        $customer = null;
        $user = auth()->user();

        // Get all companies
        $companies = $user->companies()->enabled()->limit(10)->get()->each(function ($com) {
            $com->setSettings();
        })->sortBy('name');

        // Get customer
        if ($user->customer) {
            $customer = $user;
        }

        $view->with(['companies' => $companies, 'customer' => $customer]);
    }
}
