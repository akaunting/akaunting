<?php

namespace App\Listeners\Update\V33;

use App\Abstracts\Listeners\Update as Listener;
use App\Events\Install\UpdateFinished as Event;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Version331 extends Listener
{
    const ALIAS = 'core';

    const VERSION = '3.3.1';

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

        Log::channel('stdout')->info('Updating to 3.3.1 version...');

        $this->updateMenuFavorites();

        $this->updateDocumentDiscounts();

        $this->clearCache();

        Log::channel('stdout')->info('Done!');
    }

    /**
     * Until 3.3.1 the menu stored the title and url of a favorite escaped twice, once by Blade for the
     * page's favorite slot attribute and again by the Livewire tag, so "Profit & Loss" showed as
     * "Profit &amp; Loss". Undo both escapes in the stored JSON of every company; a decoded quote
     * stays escaped for JSON.
     */
    public function updateMenuFavorites(): void
    {
        Log::channel('stdout')->info('Updating menu favorites...');

        $entities = [
            '&amp;quot;' => '\"',
            '&amp;#039;' => "'",
            '&amp;lt;' => '<',
            '&amp;gt;' => '>',
            '&amp;amp;' => '&',
        ];

        $value = DB::getQueryGrammar()->wrap('value');

        foreach ($entities as $search => $replace) {
            $value = 'REPLACE(' . $value . ', ' . DB::escape($search) . ', ' . DB::escape($replace) . ')';
        }

        $count = DB::table('settings')
            ->where('key', 'like', 'favorites.menu.%')
            ->update(['value' => DB::raw($value)]);

        Log::channel('stdout')->info("Menu favorites updated. Updated rows: {$count}.");
    }

    /**
     * Documents saved before 3.1.15 never stored the rate and type of their discount, which is only
     * in their discount total, so the form opened them without one and saving them took it off the
     * total. The API also accepts a discount without a type, which the total works out as an amount
     * while the form showed it as a percentage. Store what each discount was worked out with.
     */
    public function updateDocumentDiscounts(): void
    {
        Log::channel('stdout')->info('Updating document discounts...');

        $count = 0;

        DB::table('documents')
            ->select('id', 'company_id', 'currency_code')
            ->whereNull('discount_rate')
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('document_totals')
                    ->whereColumn('document_totals.document_id', 'documents.id')
                    ->where('document_totals.code', 'discount')
                    ->where('document_totals.amount', '>', 0)
                    ->whereNull('document_totals.deleted_at');
            })
            ->chunkById(200, function ($documents) use (&$count) {
                $totals = DB::table('document_totals')
                    ->whereIn('document_id', $documents->pluck('id'))
                    ->whereIn('code', ['sub_total', 'discount'])
                    ->whereNull('deleted_at')
                    ->get()
                    ->groupBy('document_id');

                foreach ($documents as $document) {
                    $document_totals = $totals->get($document->id, collect());

                    $discount = (float) $document_totals->where('code', 'discount')->sum('amount');
                    $sub_total = (float) $document_totals->where('code', 'sub_total')->sum('amount');

                    [$type, $rate] = $this->getDocumentDiscount($discount, $sub_total, $this->getDocumentPrecision($document));

                    $count += DB::table('documents')
                        ->where('id', $document->id)
                        ->update(['discount_type' => $type, 'discount_rate' => $rate]);
                }
            });

        $count += DB::table('documents')
            ->whereNull('discount_type')
            ->where('discount_rate', '>', 0)
            ->update(['discount_type' => 'fixed']);

        Log::channel('stdout')->info("Document discounts updated. Updated rows: {$count}.");
    }

    /**
     * A percentage when one comes to the same discount, as these documents showed it, otherwise
     * the amount. Both give the document the same total.
     */
    protected function getDocumentDiscount(float $discount, float $sub_total, int $precision): array
    {
        if ($sub_total > 0) {
            $percentage = round($discount * 100 / $sub_total, 2);

            if (round($sub_total * $percentage / 100, $precision) == round($discount, $precision)) {
                return ['percentage', $percentage];
            }
        }

        return ['fixed', $discount];
    }

    protected function getDocumentPrecision(object $document): int
    {
        $precision = DB::table('currencies')
            ->where('company_id', $document->company_id)
            ->where('code', $document->currency_code)
            ->whereNull('deleted_at')
            ->value('precision');

        if (! is_numeric($precision)) {
            $precision = config('money.currencies.' . $document->currency_code . '.precision', 2);
        }

        return (int) $precision;
    }

    public function clearCache(): void
    {
        Log::channel('stdout')->info('Clearing cache...');

        Artisan::call('cache:clear');

        Log::channel('stdout')->info('Cleared cache.');
    }
}
