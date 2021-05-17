<?php

namespace App\Listeners\Update\V21;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Models\Common\Media;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

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

        $this->updateMediaTables();

        Artisan::call('migrate', ['--force' => true]);
    }

    public function updateMediaTables()
    {
        $company_ids = [];

        foreach (Media::withTrashed()->cursor() as $media) {
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
    }
}
