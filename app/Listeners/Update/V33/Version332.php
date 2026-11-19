<?php

namespace App\Listeners\Update\V33;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use App\Traits\Categories;
use App\Traits\Documents;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Version332 extends Listener
{
    use Categories, Documents;

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

        $this->updateDefaultItemCategory();

        $this->updateItemCategories();

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

    /**
     * 3.3.2 adds the default item category, which the item form starts with and the items created from an
     * invoice or bill get. A new company gets its "General" category, the existing ones get their first
     * enabled item category, which is the same one unless it was deleted or disabled. A company that set it
     * already keeps its own.
     */
    public function updateDefaultItemCategory(): void
    {
        Log::channel('stdout')->info('Updating default item categories...');

        $rows = DB::table('categories')
            ->select('company_id', DB::raw('MIN(id) as category_id'))
            ->whereIn('type', $this->getItemCategoryTypes())
            ->where('enabled', 1)
            ->whereNull('deleted_at')
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('settings')
                    ->whereColumn('settings.company_id', 'categories.company_id')
                    ->where('settings.key', 'default.item_category')
                    ->whereNotNull('settings.value')
                    ->where('settings.value', '<>', '')
                    ->whereNull('settings.deleted_at');
            })
            ->groupBy('company_id')
            ->get()
            ->all();

        // A company that left the setting empty has a row for it already.
        foreach ($rows as $row) {
            DB::table('settings')->updateOrInsert(
                ['company_id' => $row->company_id, 'key' => 'default.item_category', 'deleted_at' => null],
                ['value' => (string) $row->category_id]
            );
        }

        $count = count($rows);

        Log::channel('stdout')->info("Default item categories updated. Updated companies: {$count}.");
    }

    /**
     * From 3.2.0 until 3.3.2 an item created from an invoice or bill line got the category of the line, i.e.
     * the income or expense category of the document, or the account of the line with Double Entry. The item
     * form does not list these, an item can have an item category only. Give these items the default item
     * category of their company, or none when the company has no item category.
     */
    public function updateItemCategories(): void
    {
        Log::channel('stdout')->info('Updating item categories...');

        $item_types = $this->getItemCategoryTypes();

        $company_ids = DB::table('items')
            ->join('categories', 'categories.id', '=', 'items.category_id')
            ->whereNotIn('categories.type', $item_types)
            ->distinct()
            ->pluck('items.company_id');

        $defaults = DB::table('settings')
            ->where('key', 'default.item_category')
            ->whereNull('deleted_at')
            ->whereIn('company_id', $company_ids)
            ->pluck('value', 'company_id');

        $count = 0;

        foreach ($company_ids as $company_id) {
            $default = $defaults[$company_id] ?? null;

            $count += DB::table('items')
                ->where('company_id', $company_id)
                ->whereIn('category_id', function ($query) use ($company_id, $item_types) {
                    $query->select('id')
                        ->from('categories')
                        ->where('company_id', $company_id)
                        ->whereNotIn('type', $item_types);
                })
                ->update(['category_id' => ! empty($default) ? (int) $default : null]);
        }

        Log::channel('stdout')->info("Item categories updated. Updated rows: {$count}.");
    }

    public function clearCache(): void
    {
        Log::channel('stdout')->info('Clearing cache...');

        Artisan::call('cache:clear');

        Log::channel('stdout')->info('Cleared cache.');
    }
}
