<?php

namespace Modules\OfflinePayment\Listeners;

use App\Events\AdminMenuCreated;

class AdminMenu
{

    /**
     * Handle the event.
     *
     * @param  AdminMenuCreated $event
     * @return void
     */
    public function handle(AdminMenuCreated $event)
    {
        $user = auth()->user();

        // Settings
        if ($user->can(['read-settings-settings', 'read-settings-categories', 'read-settings-currencies', 'read-settings-taxes'])) {
            // Add child to existing item
            $item = $event->menu->whereTitle(trans_choice('general.settings', 2));

            $item->url('apps/offlinepayment/settings', trans('offlinepayment::general.title'), 4, ['icon' => 'fa fa-angle-double-right']);
        }
    }
}
