<?php

namespace App\Listeners\Update\V30;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Traits\Permissions;
use Illuminate\Support\Facades\Log;

class Version309 extends Listener
{
    use Permissions;

    const ALIAS = 'core';

    const VERSION = '3.0.9';

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

        Log::channel('stdout')->info('Updating to 3.0.9 version...');

        $this->updatePermissions();

        Log::channel('stdout')->info('Done!');
    }

    public function updatePermissions(): void
    {
        Log::channel('stdout')->info('Updating permissions...');

        $rows = [
            'accountant' => [
                'auth-profile' => 'r,u',
                'common-reports' => 'r',
                'widgets-account-balance' => 'r',
                'widgets-bank-feeds' => 'r',
                'widgets-cash-flow' => 'r',
                'widgets-currencies' => 'r',
                'widgets-expenses-by-category' => 'r',
                'widgets-payables' => 'r',
                'widgets-profit-loss' => 'r',
                'widgets-receivables' => 'r',
            ],
        ];

        Log::channel('stdout')->info('Attaching new permissions...');

        // c=create, r=read, u=update, d=delete
        $this->attachPermissionsByRoleNames($rows);

        Log::channel('stdout')->info('Permissions updated.');
    }
}
