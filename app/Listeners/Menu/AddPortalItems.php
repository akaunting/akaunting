<?php

namespace App\Listeners\Menu;

use App\Events\Menu\PortalCreated as Event;

class AddPortalItems
{
    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $user = user();
        $menu = $event->menu;

        $menu->route('portal.dashboard', trans_choice('general.dashboards', 1), [], 100, ['icon' => 'fa fa-tachometer-alt']);

        if ($user->can('read-portal-invoices')) {
            $menu->route('portal.invoices.index', trans_choice('general.invoices', 2), [], 200, ['icon' => 'fa fa-file-signature']);
        }

        if ($user->can('read-portal-payments')) {
            $menu->route('portal.payments.index', trans_choice('general.payments', 2), [], 300, ['icon' => 'fa fa-money-bill']);
        }
    }
}
