<?php

namespace App\Listeners\Menu;

use App\Events\Menu\AdminCreated as Event;
use App\Traits\Permissions;

class AddAdminItems
{
    use Permissions;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $menu = $event->menu;

        $attr = ['icon' => ''];

        // Dashboards
        $title = trim(trans_choice('general.dashboards', 2));
        if ($this->canAccessMenuItem($title, 'read-common-dashboards')) {
            $dashboards = user()->dashboards()->enabled()->get();

            if ($dashboards->count() > 1) {
                $menu->dropdown($title, function ($sub) use ($attr, $dashboards) {
                    foreach ($dashboards as $key => $dashboard) {
                        if (session('dashboard_id') != $dashboard->id) {
                            $sub->route('dashboards.switch', $dashboard->name, ['dashboard' => $dashboard->id], $key, $attr);
                        } else {
                            $sub->url('/' . company_id(), $dashboard->name, $key, $attr);
                        }
                    }
                }, 10, [
                    'url' => '/' . company_id(),
                    'title' => $title,
                    'icon' => 'fa fa-tachometer-alt',
                ]);
            } else {
                $menu->add([
                    'url' => '/' . company_id(),
                    'title' => trans_choice('general.dashboards', 1),
                    'icon' => 'fa fa-tachometer-alt',
                    'order' => 10,
                ]);
            }
        }

        // Items
        $title = trim(trans_choice('general.items', 2));
        if ($this->canAccessMenuItem($title, 'read-common-items')) {
            $menu->route('items.index', $title, [], 20, ['icon' => 'fa fa-cube']);
        }

        // Sales
        $title = trim(trans_choice('general.sales', 2));
        if ($this->canAccessMenuItem($title, ['read-sales-invoices', 'read-sales-revenues', 'read-sales-customers'])) {
            $menu->dropdown($title, function ($sub) use ($attr) {
                $title = trim(trans_choice('general.invoices', 2));
                if ($this->canAccessMenuItem($title, 'read-sales-invoices')) {
                    $sub->route('invoices.index', $title, [], 10, $attr);
                }

                $title = trim(trans_choice('general.revenues', 2));
                if ($this->canAccessMenuItem($title, 'read-sales-revenues')) {
                    $sub->route('revenues.index', $title, [], 20, $attr);
                }

                $title = trim(trans_choice('general.customers', 2));
                if ($this->canAccessMenuItem($title, 'read-sales-customers')) {
                    $sub->route('customers.index', $title, [], 30, $attr);
                }
            }, 30, [
                'title' => $title,
                'icon' => 'fa fa-money-bill',
            ]);
        }

        // Purchases
        $title = trim(trans_choice('general.purchases', 2));
        if ($this->canAccessMenuItem($title, ['read-purchases-bills', 'read-purchases-payments', 'read-purchases-vendors'])) {
            $menu->dropdown($title, function ($sub) use ($attr) {
                $title = trim(trans_choice('general.bills', 2));
                if ($this->canAccessMenuItem($title, 'read-purchases-bills')) {
                    $sub->route('bills.index', $title, [], 10, $attr);
                }

                $title = trim(trans_choice('general.payments', 2));
                if ($this->canAccessMenuItem($title, 'read-purchases-payments')) {
                    $sub->route('payments.index', $title, [], 20, $attr);
                }

                $title = trim(trans_choice('general.vendors', 2));
                if ($this->canAccessMenuItem($title, 'read-purchases-vendors')) {
                    $sub->route('vendors.index', $title, [], 30, $attr);
                }
            }, 40, [
                'title' => $title,
                'icon' => 'fa fa-shopping-cart',
            ]);
        }

        // Banking
        $title = trim(trans('general.banking'));
        if ($this->canAccessMenuItem($title, ['read-banking-accounts', 'read-banking-transfers', 'read-banking-transactions', 'read-banking-reconciliations'])) {
            $menu->dropdown($title, function ($sub) use ($attr) {
                $title = trim(trans_choice('general.accounts', 2));
                if ($this->canAccessMenuItem($title, 'read-banking-accounts')) {
                    $sub->route('accounts.index', $title, [], 10, $attr);
                }

                $title = trim(trans_choice('general.transfers', 2));
                if ($this->canAccessMenuItem($title, 'read-banking-transfers')) {
                    $sub->route('transfers.index', $title, [], 20, $attr);
                }

                $title = trim(trans_choice('general.transactions', 2));
                if ($this->canAccessMenuItem($title, 'read-banking-transactions')) {
                    $sub->route('transactions.index', $title, [], 30, $attr);
                }

                $title = trim(trans_choice('general.reconciliations', 2));
                if ($this->canAccessMenuItem($title, 'read-banking-reconciliations')) {
                    $sub->route('reconciliations.index', $title, [], 40, $attr);
                }
            }, 50, [
                'title' => $title,
                'icon' => 'fa fa-briefcase',
            ]);
        }

        // Reports
        $title = trim(trans_choice('general.reports', 2));
        if ($this->canAccessMenuItem($title, 'read-common-reports')) {
            $menu->route('reports.index', $title, [], 60, ['icon' => 'fa fa-chart-pie']);
        }

        // Settings
        $title = trim(trans_choice('general.settings', 2));
        if ($this->canAccessMenuItem($title, 'read-settings-settings')) {
            $menu->route('settings.index', $title, [], 70, ['icon' => 'fa fa-cog']);
        }

        // Apps
        $title = trim(trans_choice('general.modules', 2));
        if ($this->canAccessMenuItem($title, 'read-modules-home')) {
            $menu->route('apps.home.index', $title, [], 80, ['icon' => 'fa fa-rocket']);
        }
    }
}
