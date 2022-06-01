<?php

namespace App\Listeners\Menu;

use App\Traits\Permissions;
use App\Events\Menu\AdminCreated as Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class ShowInAdmin
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
        $title = trim(trans_choice('general.dashboards', 1));
        if ($this->canAccessMenuItem($title, 'read-common-dashboards')) {
            $inactive = ('dashboard' != Route::currentRouteName()) ? true : false;
            $menu->route('dashboard', $title, [], 10, ['icon' => 'speed', 'inactive' => $inactive]);
        }

        // Items
        $title = trim(trans_choice('general.items', 2));
        if ($this->canAccessMenuItem($title, 'read-common-items')) {
            $menu->route('items.index', $title, [], 20, ['icon' => 'inventory_2']);
        }

        // Sales
        $title = trim(trans_choice('general.sales', 2));
        if ($this->canAccessMenuItem($title, ['read-sales-invoices', 'read-sales-customers'])) {
            $menu->dropdown($title, function ($sub) use ($attr) {
                $title = trim(trans_choice('general.invoices', 2));
                if ($this->canAccessMenuItem($title, 'read-sales-invoices')) {
                    $sub->route('invoices.index', $title, [], 10, $attr);
                }

                $title = trim(trans_choice('general.customers', 2));
                if ($this->canAccessMenuItem($title, 'read-sales-customers')) {
                    $sub->route('customers.index', $title, [], 20, $attr);
                }
            }, 30, [
                'title' => $title,
                'icon' => 'payments',
            ]);
        }

        // Purchases
        $title = trim(trans_choice('general.purchases', 2));
        if ($this->canAccessMenuItem($title, ['read-purchases-bills', 'read-purchases-vendors'])) {
            $menu->dropdown($title, function ($sub) use ($attr) {
                $title = trim(trans_choice('general.bills', 2));
                if ($this->canAccessMenuItem($title, 'read-purchases-bills')) {
                    $sub->route('bills.index', $title, [], 10, $attr);
                }

                $title = trim(trans_choice('general.vendors', 2));
                if ($this->canAccessMenuItem($title, 'read-purchases-vendors')) {
                    $sub->route('vendors.index', $title, [], 20, $attr);
                }
            }, 40, [
                'title' => $title,
                'icon' => 'shopping_cart',
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

                $title = trim(trans_choice('general.transactions', 2));
                if ($this->canAccessMenuItem($title, 'read-banking-transactions')) {
                    $sub->route('transactions.index', $title, [], 20, $attr);
                }

                $title = trim(trans_choice('general.transfers', 2));
                if ($this->canAccessMenuItem($title, 'read-banking-transfers')) {
                    $sub->route('transfers.index', $title, [], 30, $attr);
                }

                $title = trim(trans_choice('general.reconciliations', 2));
                if ($this->canAccessMenuItem($title, 'read-banking-reconciliations')) {
                    $sub->route('reconciliations.index', $title, [], 40, $attr);
                }
            }, 50, [
                'title' => $title,
                'icon' => 'account_balance',
            ]);
        }

        // Reports
        $title = trim(trans_choice('general.reports', 2));
        if ($this->canAccessMenuItem($title, 'read-common-reports')) {
            $menu->route('reports.index', $title, [], 60, ['icon' => 'donut_small']);
        }

        // Apps
        $title = trim(trans_choice('general.modules', 2));
        if ($this->canAccessMenuItem($title, 'read-modules-home')) {
            $active = (Str::contains(Route::currentRouteName(), 'apps')) ? true : false;
            $menu->route('apps.home.index', $title, [], 80, ['icon' => 'rocket_launch', 'active' => $active]);
        }
    }
}
