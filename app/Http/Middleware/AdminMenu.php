<?php

namespace App\Http\Middleware;

use App\Models\Module\Module;
use App\Events\AdminMenuCreated;
use Auth;
use Closure;
use Menu;
use Module as LaravelModule;

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
        if (!Auth::check()) {
            return $next($request);
        }

        // Setup the admin menu
        Menu::create('AdminMenu', function ($menu) {
            $menu->style('adminlte');

            $user = Auth::user();
            $attr = ['icon' => 'fa fa-angle-double-right'];

            // Dashboard
            $menu->add([
                'url' => '/',
                'title' => trans('general.dashboard'),
                'icon' => 'fa fa-dashboard',
                'order' => 1,
            ]);

            // Items
            if ($user->can('read-common-items')) {
                $menu->add([
                    'url' => 'common/items',
                    'title' => trans_choice('general.items', 2),
                    'icon' => 'fa fa-cubes',
                    'order' => 2,
                ]);
            }

            // Incomes
            if ($user->can(['read-incomes-invoices', 'read-incomes-revenues', 'read-incomes-customers'])) {
                $menu->dropdown(trans_choice('general.incomes', 2), function ($sub) use($user, $attr) {
                    if ($user->can('read-incomes-invoices')) {
                        $sub->url('incomes/invoices', trans_choice('general.invoices', 2), 1, $attr);
                    }

                    if ($user->can('read-incomes-revenues')) {
                        $sub->url('incomes/revenues', trans_choice('general.revenues', 2), 2, $attr);
                    }

                    if ($user->can('read-incomes-customers')) {
                        $sub->url('incomes/customers', trans_choice('general.customers', 2), 3, $attr);
                    }
                }, 3, [
                    'title' => trans_choice('general.incomes', 2),
                    'icon' => 'fa fa-money',
                ]);
            }

            // Expenses
            if ($user->can(['read-expenses-bills', 'read-expenses-payments', 'read-expenses-vendors'])) {
                $menu->dropdown(trans_choice('general.expenses', 2), function ($sub) use($user, $attr) {
                    if ($user->can('read-expenses-bills')) {
                        $sub->url('expenses/bills', trans_choice('general.bills', 2), 1, $attr);
                    }

                    if ($user->can('read-expenses-payments')) {
                        $sub->url('expenses/payments', trans_choice('general.payments', 2), 2, $attr);
                    }

                    if ($user->can('read-expenses-vendors')) {
                        $sub->url('expenses/vendors', trans_choice('general.vendors', 2), 3, $attr);
                    }
                }, 4, [
                    'title' => trans_choice('general.expenses', 2),
                    'icon' => 'fa fa-shopping-cart',
                ]);
            }

            // Banking
            if ($user->can(['read-banking-accounts', 'read-banking-transfers', 'read-banking-transactions', 'read-banking-reconciliations'])) {
                $menu->dropdown(trans('general.banking'), function ($sub) use($user, $attr) {
                    if ($user->can('read-banking-accounts')) {
                        $sub->url('banking/accounts', trans_choice('general.accounts', 2), 1, $attr);
                    }

                    if ($user->can('read-banking-transfers')) {
                        $sub->url('banking/transfers', trans_choice('general.transfers', 2), 2, $attr);
                    }

                    if ($user->can('read-banking-transactions')) {
                        $sub->url('banking/transactions', trans_choice('general.transactions', 2), 3, $attr);
                    }

                    if ($user->can('read-banking-reconciliations')) {
                        $sub->url('banking/reconciliations', trans_choice('general.reconciliations', 2), 4, $attr);
                    }
                }, 5, [
                    'title' => trans('general.banking'),
                    'icon' => 'fa fa-university',
                ]);
            }

            // Reports
            if ($user->can([
                'read-reports-income-summary',
                'read-reports-expense-summary',
                'read-reports-income-expense-summary',
                'read-reports-tax-summary',
                'read-reports-profit-loss',
            ])) {
                $menu->dropdown(trans_choice('general.reports', 2), function ($sub) use($user, $attr) {
                    if ($user->can('read-reports-income-summary')) {
                        $sub->url('reports/income-summary', trans('reports.summary.income'), 1, $attr);
                    }

                    if ($user->can('read-reports-expense-summary')) {
                        $sub->url('reports/expense-summary', trans('reports.summary.expense'), 2, $attr);
                    }

                    if ($user->can('read-reports-income-expense-summary')) {
                        $sub->url('reports/income-expense-summary', trans('reports.summary.income_expense'), 3, $attr);
                    }

                    if ($user->can('read-reports-tax-summary')) {
                        $sub->url('reports/tax-summary', trans('reports.summary.tax'), 4, $attr);
                    }

                    if ($user->can('read-reports-profit-loss')) {
                        $sub->url('reports/profit-loss', trans('reports.profit_loss'), 5, $attr);
                    }
                }, 6, [
                    'title' => trans_choice('general.reports', 2),
                    'icon' => 'fa fa-bar-chart',
                ]);
            }

            // Settings
            if ($user->can(['read-settings-settings', 'read-settings-categories', 'read-settings-currencies', 'read-settings-taxes'])) {
                $menu->dropdown(trans_choice('general.settings', 2), function ($sub) use($user, $attr) {
                    if ($user->can('read-settings-settings')) {
                        $sub->url('settings/settings', trans('general.general'), 1, $attr);
                    }

                    if ($user->can('read-settings-categories')) {
                        $sub->url('settings/categories', trans_choice('general.categories', 2), 2, $attr);
                    }

                    if ($user->can('read-settings-currencies')) {
                        $sub->url('settings/currencies', trans_choice('general.currencies', 2), 3, $attr);
                    }

                    if ($user->can('read-settings-taxes')) {
                        $sub->url('settings/taxes', trans_choice('general.tax_rates', 2), 4, $attr);
                    }

                    // Modules
                    $modules = Module::all();
                    $position = 5;
                    foreach ($modules as $module) {
                        if (!$module->status) {
                            continue;
                        }

                        $m = LaravelModule::findByAlias($module->alias);

                        // Check if the module exists and has settings
                        if (!$m || empty($m->get('settings'))) {
                            continue;
                        }

                        $sub->url('settings/apps/' . $module->alias, title_case(str_replace('_', ' ', snake_case($m->getName()))), $position, $attr);

                        $position++;
                    }
                }, 7, [
                    'title' => trans_choice('general.settings', 2),
                    'icon' => 'fa fa-gears',
                ]);
            }

            // Apps
            if ($user->can('read-modules-home')) {
                $menu->add([
                    'url' => 'apps/home',
                    'title' => trans_choice('general.modules', 2),
                    'icon' => 'fa fa-rocket',
                    'order' => 8,
                ]);
            }

            // Fire the event to extend the menu
            event(new AdminMenuCreated($menu));
        });

        return $next($request);
    }
}
