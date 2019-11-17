<?php

namespace App\Http\Middleware;

use App\Models\Common\Dashboard;
use Closure;

class AdminMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if logged in
        if (!auth()->check()) {
            return $next($request);
        }

        // Setup the admin menu
        menu()->create('admin', function ($menu) {
            event(new \App\Events\Menu\AdminCreating($menu));

            $menu->style('argon');

            $user = user();
            $attr = ['icon' => ''];

            // Dashboard
            $dashboards = Dashboard::getByUser($user->id);

            if ($dashboards->count() > 1) {
                $menu->dropdown(trim(trans_choice('general.dashboards', 2)), function ($sub) use ($user, $attr, $dashboards) {
                    foreach ($dashboards as $key => $dashboard) {
                        $path = (session('dashboard_id') == $dashboard->id) ? '/' : '/?dashboard_id=' . $dashboard->id;

                        $sub->url($path, $dashboard->name, $key, $attr);
                    }
                }, 1, [
                    'url' => '/',
                    'title' => trans_choice('general.incomes', 2),
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

            // Items
            if ($user->can('read-common-items')) {
                $menu->route('items.index', trans_choice('general.items', 2), [], 2, ['icon' => 'fa fa-cube']);
            }

            // Incomes
            if ($user->can(['read-incomes-invoices', 'read-incomes-revenues', 'read-incomes-customers'])) {
                $menu->dropdown(trim(trans_choice('general.incomes', 2)), function ($sub) use ($user, $attr) {
                    if ($user->can('read-incomes-invoices')) {
                        $sub->route('invoices.index', trans_choice('general.invoices', 2), [], 1, $attr);
                    }

                    if ($user->can('read-incomes-revenues')) {
                        $sub->route('revenues.index', trans_choice('general.revenues', 2), [], 2, $attr);
                    }

                    if ($user->can('read-incomes-customers')) {
                        $sub->route('customers.index', trans_choice('general.customers', 2), [], 3, $attr);
                    }
                }, 3, [
                    'title' => trans_choice('general.incomes', 2),
                    'icon' => 'fa fa-money-bill',
                ]);
            }

            // Expenses
            if ($user->can(['read-expenses-bills', 'read-expenses-payments', 'read-expenses-vendors'])) {
                $menu->dropdown(trim(trans_choice('general.expenses', 2)), function ($sub) use ($user, $attr) {
                    if ($user->can('read-expenses-bills')) {
                        $sub->route('bills.index', trans_choice('general.bills', 2), [], 1, $attr);
                    }

                    if ($user->can('read-expenses-payments')) {
                        $sub->route('payments.index', trans_choice('general.payments', 2), [], 2, $attr);
                    }

                    if ($user->can('read-expenses-vendors')) {
                        $sub->route('vendors.index', trans_choice('general.vendors', 2), [], 3, $attr);
                    }
                }, 4, [
                    'title' => trans_choice('general.expenses', 2),
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

            event(new \App\Events\Menu\AdminCreated($menu));
        });

        return $next($request);
    }
}
