<?php

namespace App\Listeners\Updates\V13;

use App\Events\UpdateFinished;
use App\Listeners\Updates\Listener;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use Artisan;

class Version132 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.3.2';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(UpdateFinished $event)
    {
        // Check if should listen
        if (!$this->check($event)) {
            return;
        }

        $this->updatePermissions();

        // Update database
        Artisan::call('migrate', ['--force' => true]);
    }

    protected function updatePermissions()
    {
        $permissions = [];

        // Banking Reconciliations
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-common-notifications',
            'display_name' => 'Read Common Notifications',
            'description' => 'Read Common Notifications',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-common-notifications',
            'display_name' => 'Create Common Notifications',
            'description' => 'Create Common Notifications',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-common-notifications',
            'display_name' => 'Update Common Notifications',
            'description' => 'Update Common Notifications',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-common-notifications',
            'display_name' => 'Delete Common Notifications',
            'description' => 'Delete Common Notifications',
        ]);

        // Attach permission to roles
        $roles = Role::all();

        foreach ($roles as $role) {
            $allowed = ['admin', 'manager'];

            if (!in_array($role->name, $allowed)) {
                continue;
            }

            foreach ($permissions as $permission) {
                $role->attachPermission($permission);
            }
        }
    }
}
