<?php

namespace App\Listeners\Update\V21;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\Media;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Scopes\Company;
use Illuminate\Support\Facades\Schema;

class Version2114 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '2.1.14';

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

        $this->updateMediaTables();
    }

    public function updateMediaTables()
    {
        $company_ids = [];

        foreach (Media::withTrashed()->withoutGlobalScope(Company::class)->cursor() as $media) {
            $company_id = null;

            if (preg_match('/\d{4}(\/\d{2}){2}\/(\d+)\//', $media->directory, $matches) && isset($matches[2])) { // 2021/04/09/34235/invoices
                $company_id = $matches[2];
            } elseif (preg_match('/^(\d+)\//', $media->directory, $matches) && isset($matches[1])) { // 34235/invoices
                $company_id = $matches[1];
            }

            if (null === $company_id) {
                continue;
            }

            $company_ids[$company_id][] = $media->id;
        }

        foreach ($company_ids as $company_id => $media_ids) {
            DB::table('media')->whereIn('id', $media_ids)->update(['company_id' => $company_id]);
            DB::table('mediables')->whereIn('media_id', $media_ids)->update(['company_id' => $company_id]);
        }

        Schema::table('media', function (Blueprint $table) {
            $table->unsignedInteger('company_id')->default(null)->change();
        });

        Schema::table('mediables', function (Blueprint $table) {
            $table->unsignedInteger('company_id')->default(null)->change();
        });
    }
}
