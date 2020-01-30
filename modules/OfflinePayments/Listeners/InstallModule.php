<?php

namespace Modules\OfflinePayments\Listeners;

use App\Events\Module\Installed as Event;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;

class InstallModule
{
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
        $permissions = [];

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-offline-payments-settings'
        ], [
            'display_name' => 'Read Offline Payments Settings',
            'description' => 'Read Offline Payments Settings',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-offline-payments-settings'
        ], [
            'display_name' => 'Update Offline Payments Settings',
            'description' => 'Update Offline Payments Settings',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-offline-payments-settings'
        ], [
            'display_name' => 'Delete Offline Payments Settings',
            'description' => 'Delete Offline Payments Settings',
        ]);

        $roles = Role::all()->filter(function ($r) {
            return $r->hasPermission('read-admin-panel');
        });

        foreach ($roles as $role) {
            foreach ($permissions as $permission) {
                if ($role->hasPermission($permission->name)) {
                    continue;
                }

                $role->attachPermission($permission);
            }
        }
    }
}
