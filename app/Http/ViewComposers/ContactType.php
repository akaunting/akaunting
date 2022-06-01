<?php

namespace App\Http\ViewComposers;

use App\Http\Controllers\Purchases\Vendors;
use App\Http\Controllers\Sales\Customers;
use Illuminate\View\View;

class ContactType
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $route = request()->route();

        if (empty($route)) {
            return;
        }

        /** @var Customers|Vendors $controller */
        $controller = $route->getController();

        $view->with(['type' => $controller->type ?? '']);
    }
}
