<?php

namespace App\Listeners\Menu;

use App\Events\Menu\PortalCreated as Event;

class ShowInPortal
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

        $inactive = false;

        if (request()->route()->getName() != 'portal.dashboard') {
            $inactive = true;
        }

        $menu->route('portal.dashboard', trans_choice('general.dashboards', 1), [], 10, ['icon' => 'speed', 'inactive' => $inactive]);

        if ($user->can('read-portal-invoices')) {
            $menu->route('portal.invoices.index', trans_choice('general.invoices', 2), [], 20, ['icon' => 'description']);
        }

        if ($user->can('read-portal-payments')) {
            $menu->route('portal.payments.index', trans_choice('general.payments', 2), [], 30, ['icon' => 'credit_score']);
        }
    }
}
