<?php

namespace App\Listeners\Updates\V13;

use App\Events\UpdateFinished;
use App\Listeners\Updates\Listener;
use App\Models\Auth\Role;
use App\Models\Auth\Permission;
use Artisan;

class Version1311 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.3.11';

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

        // Common Uploads
        $permissions[] = Permission::firstOrCreate([
            'name' => 'read-common-uploads',
            'display_name' => 'Read Common Uploads',
            'description' => 'Read Common Uploads',
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
