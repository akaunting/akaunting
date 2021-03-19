<?php

namespace App\Listeners\Update\V21;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Traits\Permissions;

class Version218 extends Listener
{
    use Permissions;

    const ALIAS = 'core';

    const VERSION = '2.1.8';

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

        $this->updatePermissions();
    }

    public function updatePermissions()
    {
        // c=create, r=read, u=update, d=delete
        $this->attachPermissionsByRoleNames([
            'admin' => [
                'widgets-currencies' => 'r',
            ],
            'manager' => [
                'widgets-currencies' => 'r',
            ],
        ]);
    }
}
