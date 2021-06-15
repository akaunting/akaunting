<?php

namespace App\Listeners\Update\V21;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class Version2116 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.1.16';

    /**
     * Handle the event.
     *
     * @param  $event
     *
     * @return void
     */
    public function handle(Event $event)
    {
        if ($this->skipThisUpdate($event)) {
            return;
        }

        $migration = DB::table('migrations')
                       ->where('migration', '2016_06_27_000001_create_mediable_test_tables')
                       ->first();

        if ($migration === null) {
            DB::table('migrations')->insert([
                'id' => DB::table('migrations')->max('id') + 1,
                'migration' => '2016_06_27_000001_create_mediable_test_tables',
                'batch' => DB::table('migrations')->max('batch') + 1,
            ]);
        }

        Artisan::call('migrate', ['--force' => true]);
    }
}
