<?php

namespace Modules\OfflinePayment\Events\Handlers;

use App\Events\AdminMenuCreated;
use Auth;

class OfflinePaymentAdminMenu
{
    /**
     * Handle the event.
     *
     * @param  AdminMenuCreated $event
     * @return void
     */
    public function handle(AdminMenuCreated $event)
    {
        $user = Auth::user();

        // Settings
        if ($user->can(['read-settings-settings', 'read-settings-categories', 'read-settings-currencies', 'read-settings-taxes'])) {
            // Add child to existing item
            $item = $event->menu->whereTitle(trans_choice('general.settings', 2));

            $item->url('apps/offlinepayment/settings', trans('offlinepayment::offlinepayment.offlinepayment'), 4, ['icon' => 'fa fa-angle-double-right']);
        }
    }
}
