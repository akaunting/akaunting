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
        $menu = $event->menu;

        $menu->route('portal.dashboard', trans_choice('general.dashboards', 1), [], 1, ['icon' => 'fa fa-tachometer-alt']);

        $menu->route('portal.invoices.index', trans_choice('general.invoices', 2), [], 2, ['icon' => 'fa fa-file-signature']);

        $menu->route('portal.payments.index', trans_choice('general.payments', 2), [], 3, ['icon' => 'fa fa-money-bill']);
    }
}
