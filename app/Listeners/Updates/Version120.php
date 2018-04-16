<?php

namespace App\Listeners\Updates;

use App\Events\UpdateFinished;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;

class Version120 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.2.0';

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

        // Create tax summary permission
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-reports-tax-summary',
            'display_name' => 'Read Reports Tax Summary',
            'description' => 'Read Reports Tax Summary',
        ]);

        // Create profit loss permission
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-reports-profit-loss',
            'display_name' => 'Read Reports Profit Loss',
            'description' => 'Read Reports Profit Loss',
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
