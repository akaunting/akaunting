<?php

namespace App\Listeners\Menu;

use App\Events\Menu\NewwCreated as Event;
use App\Traits\Permissions;

class ShowInNeww
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

        $title = trim(trans_choice('general.invoices', 1));
        if ($this->canAccessMenuItem($title, 'create-sales-invoices')) {
            $menu->route('invoices.create', $title, [], 10, ['icon' => 'description']);
        }

        $title = trim(trans_choice('general.incomes', 1));
        if ($this->canAccessMenuItem($title, 'create-banking-transactions')) {
            $menu->route('transactions.create', $title, ['type' => 'income'], 20, ['icon' => 'request_quote']);
        }

        $title = trim(trans_choice('general.customers', 1));
        if ($this->canAccessMenuItem($title, 'create-sales-customers')) {
            $menu->route('customers.create', $title, [], 30, ['icon' => 'person']);
        }

        $title = trim(trans_choice('general.bills', 1));
        if ($this->canAccessMenuItem($title, 'create-purchases-bills')) {
            $menu->route('bills.create', $title, [], 40, ['icon' => 'file_open']);
        }

        $title = trim(trans_choice('general.expenses', 1));
        if ($this->canAccessMenuItem($title, 'create-banking-transactions')) {
            $menu->route('transactions.create', $title, ['type' => 'expense'], 50, ['icon' => 'paid']);
        }

        $title = trim(trans_choice('general.vendors', 1));
        if ($this->canAccessMenuItem($title, 'create-purchases-vendors')) {
            $menu->route('vendors.create', $title, [], 60, ['icon' => 'engineering']);
        }

        $title = trim(trans_choice('general.transfers', 1));
        if ($this->canAccessMenuItem($title, 'create-banking-transfers')) {
            $menu->route('transfers.create', $title, [], 70, ['icon' => 'swap_horiz']);
        }
    }
}
