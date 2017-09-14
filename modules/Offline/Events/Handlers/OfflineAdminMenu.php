<?php

namespace Modules\Offline\Events\Handlers;

use App\Events\AdminMenuCreated;

class OfflineAdminMenu
{
    /**
     * Handle the event.
     *
     * @param  AdminMenuCreated $event
     * @return void
     */
    public function handle(AdminMenuCreated $event)
    {
        // Add child to existing item
        $item = $event->menu->whereTitle(trans_choice('general.settings', 2));
        $item->url('modules/offline/settings', trans('offline::offline.offline'), 4, ['icon' => 'fa fa-angle-double-right']);
    }
}
