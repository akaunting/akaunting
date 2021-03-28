<?php

namespace App\Listeners\Auth;

use App\Events\Auth\LandingPageShowing as Event;

class AddLandingPages
{
    /**
     * Handle the event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        $routes = [
            'dashboard' => trans_choice('general.dashboards', 1),
            'items.index' => trans_choice('general.items', 2),
            'invoices.index' => trans_choice('general.invoices', 2),
            'revenues.index' => trans_choice('general.revenues', 2),
            'customers.index' => trans_choice('general.customers', 2),
            'bills.index' => trans_choice('general.bills', 2),
            'payments.index' => trans_choice('general.payments', 2),
            'vendors.index' => trans_choice('general.vendors', 2),
            'accounts.index' => trans_choice('general.accounts', 2),
            'transfers.index' => trans_choice('general.transfers', 2),
            'transactions.index' => trans_choice('general.transactions', 2),
            'reconciliations.index' => trans_choice('general.reconciliations', 2),
            'reports.index' => trans_choice('general.reports', 2),
            'settings.index' => trans_choice('general.settings', 2),
            'categories.index' => trans_choice('general.categories', 2),
            'currencies.index' => trans_choice('general.currencies', 2),
            'taxes.index' => trans_choice('general.taxes', 2),
            'users.index' => trans_choice('general.users', 2),
        ];

        foreach($routes as $key => $value) {
            $event->user->landing_pages[$key] = $value;
        }
    }
}
