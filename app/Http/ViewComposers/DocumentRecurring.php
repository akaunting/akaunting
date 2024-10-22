<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use Illuminate\Support\Str;

class DocumentRecurring
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

        $type = $controller->type ?? '';

        if (! Str::contains($type, 'recurring')) {
            return;
        }

        $view->with([
            'type' => $type,
        ]);

        // Override the whole file
        $view->setPath(view('components.documents.form.recurring_metadata', compact('type'))->getPath());
    }
}
