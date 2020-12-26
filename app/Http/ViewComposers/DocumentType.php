<?php

namespace App\Http\ViewComposers;

use App\Http\Controllers\Portal\Invoices as PortalInvoices;
use App\Http\Controllers\Purchases\Bills;
use App\Http\Controllers\Sales\Invoices;
use Illuminate\View\View;

class DocumentType
{

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        if (!empty(request()->route())) {
            $route = request()->route();

            /** @var Invoices|Bills|PortalInvoices $controller */
            $controller = $route->getController();

            $view->with(['type' => $controller->type ?? '']);
        }
    }

}
