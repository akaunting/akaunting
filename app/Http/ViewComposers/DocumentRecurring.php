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

        $payment_terms = [
            '0'  => trans('settings.invoice.due_receipt'),
            '15' => trans('settings.invoice.due_days', ['days' => 15]),
            '30' => trans('settings.invoice.due_days', ['days' => 30]),
            '45' => trans('settings.invoice.due_days', ['days' => 45]),
            '60' => trans('settings.invoice.due_days', ['days' => 60]),
            '90' => trans('settings.invoice.due_days', ['days' => 90]),
        ];

        $view->with([
            'type' => $type,
            'payment_terms' => $payment_terms,
        ]);

        // Override the whole file
        $view->setPath(view('components.documents.form.recurring_metadata', compact('type', 'payment_terms'))->getPath());
    }
}
