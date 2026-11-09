<?php

namespace Tests\Feature\Document;

use App\Jobs\Document\CreateDocument;
use App\Models\Document\Document;
use App\Models\Document\DocumentTotal;
use Illuminate\Support\Facades\DB;
use Tests\Feature\FeatureTestCase;

/**
 * The title of a tax total says the rate the document was charged at, read from its item
 * taxes. Every total is serialized with its title, and the customer and vendor lists hand
 * over the invoices and bills with their totals, so that read ran once per tax total
 * (Sentry CLOUD-PROD-LARAVEL-1V3Y, an N+1 on the customer list since 2026-09).
 */
class DocumentTotalTitleTest extends FeatureTestCase
{
    public function testTheCustomerListShouldReadTheChargedRatesInOneQuery()
    {
        $this->loginAs();

        foreach (range(1, 3) as $i) {
            $this->dispatch(new CreateDocument(Document::factory()->invoice()->items()->raw()));
        }

        $queries = 0;

        DB::listen(function ($query) use (&$queries) {
            if (str_contains($query->sql, 'document_item_taxes')) {
                $queries++;
            }
        });

        $this->getJson(route('customers.index'))->assertOk();

        $this->assertSame(1, $queries);
    }

    public function testTheTitleShouldBeTheSameWithTheItemTaxesLoaded()
    {
        $this->loginAs();

        $invoice = $this->dispatch(new CreateDocument(Document::factory()->invoice()->items()->raw()));

        $queried = DocumentTotal::where('document_id', $invoice->id)->where('code', 'tax')->first();
        $loaded = DocumentTotal::with('item_taxes')->where('document_id', $invoice->id)->where('code', 'tax')->first();

        $this->assertNotNull($queried);
        $this->assertSame($queried->title, $loaded->title);

        // The relation is there for the title only
        $this->assertArrayNotHasKey('item_taxes', $loaded->toArray());
    }
}
