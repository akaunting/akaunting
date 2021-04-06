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
        $user = user();
        $routes = [
            'dashboard' => [
                'permission' => 'read-common-dashboards',
                'translate'  => trans_choice('general.dashboards', 1),
            ],
            'items.index' => [
                'permission' => 'read-common-items',
                'translate'  => trans_choice('general.items', 2),
            ],
            'invoices.index' => [
                'permission' => 'read-sales-invoices',
                'translate'  => trans_choice('general.invoices', 2),
            ],
            'revenues.index' => [
                'permission' => 'read-sales-revenues',
                'translate'  => trans_choice('general.revenues', 2),
            ],
            'customers.index' => [
                'permission' => 'read-sales-customers',
                'translate'  => trans_choice('general.customers', 2),
            ],
            'bills.index' => [
                'permission' => 'read-purchases-bills',
                'translate'  => trans_choice('general.bills', 2),
            ],
            'payments.index' => [
                'permission' => 'read-purchases-payments',
                'translate'  => trans_choice('general.payments', 2),
            ],
            'vendors.index' => [
                'permission' => 'read-purchases-vendors',
                'translate'  => trans_choice('general.vendors', 2),
            ],
            'accounts.index' => [
                'permission' => 'read-banking-accounts',
                'translate'  => trans_choice('general.accounts', 2),
            ],
            'transfers.index' => [
                'permission' => 'read-banking-transfers',
                'translate'  => trans_choice('general.transfers', 2),
            ],
            'transactions.index' => [
                'permission' => 'read-banking-transactions',
                'translate'  => trans_choice('general.transactions', 2),
            ],
            'reconciliations.index' => [
                'permission' => 'read-banking-reconciliations',
                'translate'  => trans_choice('general.reconciliations', 2),
            ],
            'reports.index' => [
                'permission' => 'read-common-reports',
                'translate'  => trans_choice('general.reports', 2),
            ],
            'settings.index' => [
                'permission' => 'read-settings-settings',
                'translate'  => trans_choice('general.settings', 2),
            ],
            'categories.index' => [
                'permission' => 'read-settings-categories',
                'translate'  => trans_choice('general.categories', 2),
            ],
            'currencies.index' => [
                'permission' => 'read-settings-currencies',
                'translate'  => trans_choice('general.currencies', 2),
            ],
            'taxes.index' => [
                'permission' => 'read-settings-taxes',
                'translate'  => trans_choice('general.taxes', 2),
            ],
            'users.index' => [
                'permission' => 'read-auth-users',
                'translate'  => trans_choice('general.users', 2),
            ],
        ];

        foreach($routes as $key => $route) {
            if (!$user->can($route['permission'])) {
                continue;
            }

            $event->user->landing_pages[$key] = $route['translate'];
        }
    }
}
