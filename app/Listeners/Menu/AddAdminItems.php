<?php

namespace App\Listeners\Menu;

use App\Events\Menu\AdminCreated as Event;

class AddAdminItems
{
    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $menu = $event->menu;

        $user = user();
        $attr = ['icon' => ''];

        // Dashboards
        if ($user->can('read-common-dashboards')) {
            $dashboards = $user->dashboards()->enabled()->get();

            if ($dashboards->count() > 1) {
                $menu->dropdown(trim(trans_choice('general.dashboards', 2)), function ($sub) use ($user, $attr, $dashboards) {
                    foreach ($dashboards as $key => $dashboard) {
                        if (session('dashboard_id') != $dashboard->id) {
                            $sub->route('dashboards.switch', $dashboard->name, ['dashboard' => $dashboard->id], $key, $attr);
                        } else {
                            $sub->url('/', $dashboard->name, $key, $attr);
                        }
                    }
                }, 1, [
                    'url' => '/',
                    'title' => trans_choice('general.dashboards', 2),
                    'icon' => 'fa fa-tachometer-alt',
                ]);
            } else {
                $menu->add([
                    'url' => '/',
                    'title' => trans_choice('general.dashboards', 1),
                    'icon' => 'fa fa-tachometer-alt',
                    'order' => 1,
                ]);
            }
        }

        // Items
        if ($user->can('read-common-items')) {
            $menu->route('items.index', trans_choice('general.items', 2), [], 2, ['icon' => 'fa fa-cube']);
        }

        // Sales
        if ($user->can(['read-sales-invoices', 'read-sales-revenues', 'read-sales-customers'])) {
            $menu->dropdown(trim(trans_choice('general.sales', 2)), function ($sub) use ($user, $attr) {
                if ($user->can('read-sales-invoices')) {
                    $sub->route('invoices.index', trans_choice('general.invoices', 2), [], 1, $attr);
                }

                if ($user->can('read-sales-revenues')) {
                    $sub->route('revenues.index', trans_choice('general.revenues', 2), [], 2, $attr);
                }

                if ($user->can('read-sales-customers')) {
                    $sub->route('customers.index', trans_choice('general.customers', 2), [], 3, $attr);
                }
            }, 3, [
                'title' => trans_choice('general.sales', 2),
                'icon' => 'fa fa-money-bill',
            ]);
        }

        // Purchases
        if ($user->can(['read-purchases-bills', 'read-purchases-payments', 'read-purchases-vendors'])) {
            $menu->dropdown(trim(trans_choice('general.purchases', 2)), function ($sub) use ($user, $attr) {
                if ($user->can('read-purchases-bills')) {
                    $sub->route('bills.index', trans_choice('general.bills', 2), [], 1, $attr);
                }

                if ($user->can('read-purchases-payments')) {
                    $sub->route('payments.index', trans_choice('general.payments', 2), [], 2, $attr);
                }

                if ($user->can('read-purchases-vendors')) {
                    $sub->route('vendors.index', trans_choice('general.vendors', 2), [], 3, $attr);
                }
            }, 4, [
                'title' => trans_choice('general.purchases', 2),
                'icon' => 'fa fa-shopping-cart',
            ]);
        }

        // Banking
        if ($user->can(['read-banking-accounts', 'read-banking-transfers', 'read-banking-transactions', 'read-banking-reconciliations'])) {
            $menu->dropdown(trim(trans('general.banking')), function ($sub) use ($user, $attr) {
                if ($user->can('read-banking-accounts')) {
                    $sub->route('accounts.index', trans_choice('general.accounts', 2), [], 1, $attr);
                }

                if ($user->can('read-banking-transfers')) {
                    $sub->route('transfers.index', trans_choice('general.transfers', 2), [], 2, $attr);
                }

                if ($user->can('read-banking-transactions')) {
                    $sub->route('transactions.index', trans_choice('general.transactions', 2), [], 3, $attr);
                }

                if ($user->can('read-banking-reconciliations')) {
                    $sub->route('reconciliations.index', trans_choice('general.reconciliations', 2), [], 4, $attr);
                }
            }, 5, [
                'title' => trans('general.banking'),
                'icon' => 'fa fa-briefcase',
            ]);
        }

        // Reports
        if ($user->can('read-common-reports')) {
            $menu->route('reports.index', trans_choice('general.reports', 2), [], 6, ['icon' => 'fa fa-chart-pie']);
        }

        // Settings
        if ($user->can('read-settings-settings')) {
            $menu->route('settings.index', trans_choice('general.settings', 2), [], 7, ['icon' => 'fa fa-cog']);
        }

        // Apps
        if ($user->can('read-modules-home')) {
            $menu->route('apps.home.index', trans_choice('general.modules', 2), [], 8, ['icon' => 'fa fa-rocket']);
        }
    }
}
