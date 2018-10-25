<?php

namespace App\Listeners\Updates;

use App\Events\UpdateFinished;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use Artisan;

class Version130 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.3.0';

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

        $permissions = $this->getPermissions();

        // Attach permission to roles
        $roles = Role::all();

        foreach ($roles as $role) {
            $allowed = ['admin'];

            if (!in_array($role->name, $allowed)) {
                continue;
            }

            foreach ($permissions as $permission) {
                $role->attachPermission($permission);
            }
        }

        // Set new Item Reminder settings
        setting(['general.send_item_reminder' => '0']);
        setting(['general.schedule_item_stocks' => '3,5,7']);
        setting(['general.wizard' => '1']);

        setting()->save();
    }

    protected function getPermissions()
    {
        $permissions = [];

        // Create permissions
        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-wizard-companies',
            'display_name' => 'Create Wizard Compaines',
            'description' => 'Create Wizard Compaines',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-wizard-currencies',
            'display_name' => 'Create Wizard Currencies',
            'description' => 'Create Wizard Currencies',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-wizard-taxes',
            'display_name' => 'Create Wizard Taxes',
            'description' => 'Create Wizard Taxes',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-wizard-finish',
            'display_name' => 'Create Wizard Finish',
            'description' => 'Create Wizard Finish',
        ]);

        // Read permissions
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-wizard-companies',
            'display_name' => 'Read Wizard Compaines',
            'description' => 'Read Wizard Compaines',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-wizard-currencies',
            'display_name' => 'Read Wizard Currencies',
            'description' => 'Read Wizard Currencies',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-wizard-taxes',
            'display_name' => 'Read Wizard Taxes',
            'description' => 'Read Wizard Taxes',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-wizard-finish',
            'display_name' => 'Read Wizard Finish',
            'description' => 'Read Wizard Finish',
        ]);

        // Update permissions
        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-wizard-companies',
            'display_name' => 'Update Wizard Compaines',
            'description' => 'Update Wizard Compaines',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-wizard-currencies',
            'display_name' => 'Update Wizard Currencies',
            'description' => 'Update Wizard Currencies',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-wizard-taxes',
            'display_name' => 'Update Wizard Taxes',
            'description' => 'Update Wizard Taxes',
        ]);

        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-wizard-finish',
            'display_name' => 'Update Wizard Finish',
            'description' => 'Update Wizard Finish',
        ]);

        return $permissions;
    }
}
