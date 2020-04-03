<?php

namespace Modules\OfflinePayments\Listeners;

use App\Events\Module\Installed as Event;
use App\Traits\Permissions;

class InstallModule
{
    use Permissions;

    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($event->alias != 'offline-payments') {
            return;
        }

        $this->updatePermissions();
    }

    protected function updatePermissions()
    {
        $this->attachPermissionsToAdminRoles([
            $this->createModuleSettingPermission('offline-payments', 'read'),
            $this->createModuleSettingPermission('offline-payments', 'update'),
            $this->createModuleSettingPermission('offline-payments', 'delete'),
        ]);
    }
}
