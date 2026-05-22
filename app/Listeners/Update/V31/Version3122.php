<?php

namespace App\Listeners\Update\V31;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Version3122 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '3.1.22';

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

        Log::channel('stdout')->info('Updating to 3.1.22 version...');

        $this->updateDatabase();

        $this->updateDocumentItems();

        Log::channel('stdout')->info('Done!');
    }

    public function updateDatabase(): void
    {
        Log::channel('stdout')->info('Updating database...');

        Artisan::call('migrate', ['--force' => true]);

        Log::channel('stdout')->info('Database updated.');
    }

    public function updateDocumentItems(): void
    {
        Log::channel('stdout')->info('Updating document items...');

        DB::table('documents')
            ->whereNotNull('category_id')
            ->chunk(500, function ($documents) {
                DB::transaction(function () use ($documents) {
                    foreach ($documents as $document) {
                        DB::table('document_items')
                            ->where('document_id', $document->id)
                            ->whereNull('category_id')
                            ->update([
                                'category_id' => $document->category_id,
                            ]);
                    }
                });
            });

        Log::channel('stdout')->info('Document items updated.');
    }
}
