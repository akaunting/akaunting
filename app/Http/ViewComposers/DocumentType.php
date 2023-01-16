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
        $route = request()->route();

        if (empty($route)) {
            return;
        }

        /** @var Invoices|Bills|PortalInvoices $controller */
        $controller = $route->getController();

        $view->with(['type' => $controller->type ?? '']);
    }
}
