<?php

namespace App\Listeners\Update\V31;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Traits\Permissions;
use Illuminate\Support\Facades\Log;

class Version3119 extends Listener
{
    use Permissions;

    const ALIAS = 'core';

    const VERSION = '3.1.19';

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

        Log::channel('stdout')->info('Updating to 3.1.19 version...');

        $this->updatePermissions();

        Log::channel('stdout')->info('Done!');
    }

    /**
     * Update permissions.
     *
     * @return void
     */
    public function updatePermissions()
    {
        $rows = [
            'admin' => [
                'reports-discount-summary' => 'r'
            ],
            'manager' => [
                'reports-discount-summary' => 'r'
            ],
            'accountant' => [
                'reports-discount-summary' => 'r'
            ],
        ];

        $this->attachPermissionsByRoleNames($rows);
    }
}
