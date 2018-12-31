<?php

namespace App\Listeners\Updates\V12;

use App\Events\UpdateFinished;
use App\Listeners\Updates\Listener;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;

class Version126 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.2.6';

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

        $permissions = [];

        // Create permission
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-modules-my',
            'display_name' => 'Read Modules My',
            'description' => 'Read Modules My',
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
