<?php

namespace App\Listeners\Module;

use App\Events\Module\Installed as Event;
use App\Traits\Permissions;
use Artisan;

class FinishInstallation
{
    use Permissions;

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        $module = module($event->alias);

        Artisan::call('module:migrate', ['alias' => $event->alias, '--force' => true]);

        $this->attachDefaultModulePermissions($module);
    }
}
