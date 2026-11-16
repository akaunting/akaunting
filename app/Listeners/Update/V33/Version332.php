<?php

namespace App\Listeners\Update\V33;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Traits\Documents;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Version332 extends Listener
{
    use Documents;

    const ALIAS = 'core';

    const VERSION = '3.3.2';

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

        Log::channel('stdout')->info('Updating to 3.3.2 version...');

        $this->updateRecurringDocumentItemTaxes();

        $this->clearCache();

        Log::channel('stdout')->info('Done!');
    }

    /**
     * Until 3.3.2 the recurring check set the type of the items and totals of the invoices and bills
     * it created from a template, but not of their item taxes, which kept the type of the template,
     * e.g. invoice-recurring. So these taxes were left out of the invoice and bill exports, and the
     * taxes they use were not counted when deleting or disabling a tax. Give them the type of their
     * document.
     */
    public function updateRecurringDocumentItemTaxes(): void
    {
        Log::channel('stdout')->info('Updating recurring document item taxes...');

        $count = 0;

        foreach ($this->getRecurringDocumentTypes() as $recurring_type) {
            $type = $this->getRealTypeOfRecurringDocument($recurring_type);

            $count += DB::table('document_item_taxes')
                ->where('type', $recurring_type)
                ->whereIn('document_id', function ($query) use ($type) {
                    $query->select('id')
                        ->from('documents')
                        ->where('type', $type);
                })
                ->update(['type' => $type]);
        }

        Log::channel('stdout')->info("Recurring document item taxes updated. Updated rows: {$count}.");
    }

    public function clearCache(): void
    {
        Log::channel('stdout')->info('Clearing cache...');

        Artisan::call('cache:clear');

        Log::channel('stdout')->info('Cleared cache.');
    }
}
