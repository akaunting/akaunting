<?php

namespace App\Listeners\Update\V20;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Traits\Permissions;
use Illuminate\Support\Facades\Artisan;

class Version2024 extends Listener
{
    use Permissions;

    const ALIAS = 'core';

    const VERSION = '2.0.24';

    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $this->updateDatabase();

        $this->updatePermissions();
    }

    public function updateDatabase()
    {
        Artisan::call('migrate', ['--force' => true]);
    }

    public function updatePermissions()
    {
        $this->attachPermissionsByRoleNames([
            'admin' => [
                'banking-transactions' => 'c,r,u,d',
                'common-notifications' => 'c,r,u,d',
                'common-uploads' => 'r,d',
            ],
            'manager' => [
                'banking-transactions' => 'c,r,u,d',
                'common-notifications' => 'c,r,u,d',
            ],
        ]);
    }
}
