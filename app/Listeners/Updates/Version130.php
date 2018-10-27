<?php

namespace App\Listeners\Updates;

use App\Events\UpdateFinished;
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

        // Set new Item Reminder settings
        setting(['general.send_item_reminder' => '0']);
        setting(['general.schedule_item_stocks' => '3,5,7']);

        setting()->save();

        $this->updatePermissions();

        // Update database
        Artisan::call('migrate', ['--force' => true]);
    }

    protected function updatePermissions()
    {
        $permissions = [];

        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-banking-reconciliations',
            'display_name' => 'Read Banking Reconciliations',
            'description' => 'Read Banking Reconciliations',
        ]);
        $permissions[] = Permission::firstOrCreate([
            'name' => 'create-banking-reconciliations',
            'display_name' => 'Create Banking Reconciliations',
            'description' => 'Create Banking Reconciliations',
        ]);
        $permissions[] = Permission::firstOrCreate([
            'name' => 'update-banking-reconciliations',
            'display_name' => 'Update Banking Reconciliations',
            'description' => 'Update Banking Reconciliations',
        ]);
        $permissions[] = Permission::firstOrCreate([
            'name' => 'delete-banking-reconciliations',
            'display_name' => 'Delete Banking Reconciliations',
            'description' => 'Delete Banking Reconciliations',
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
