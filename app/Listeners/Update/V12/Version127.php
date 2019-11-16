<?php

namespace App\Listeners\Update\V12;

use App\Events\Install\UpdateFinished as Event;
use App\Listeners\Update\Listener;
use App\Models\Auth\Permission;
use File;
use Illuminate\Support\Str;

class Version127 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '1.2.7';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        // Check if should listen
        if (!$this->check($event)) {
            return;
        }

        // Update permissions
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            if (strstr($permission->name, '-companies-companies')) {
                $permission->name = str_replace('-companies-companies', '-common-companies', $permission->name);
                $permission->save();
            }

            if (strstr($permission->name, '-items-items')) {
                $permission->name = str_replace('-items-items', '-common-items', $permission->name);
                $permission->save();
            }
        }

        // Delete folders
        $dirs = ['dashboard', 'search', 'companies', 'items'];
        foreach ($dirs as $dir) {
            File::deleteDirectory(app_path('Filters/' . ucfirst($dir)));
            File::deleteDirectory(app_path('Http/Controllers/' . ucfirst($dir)));
            File::deleteDirectory(app_path('Http/Requests/' . ucfirst(Str::singular($dir))));
            File::deleteDirectory(resource_path('views/' . $dir));
        }
    }
}
