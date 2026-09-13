<?php

namespace Tests\Feature\Document;

use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\UpdateDocument;
use App\Models\Document\Document;
use App\Models\Setting\Tax;
use Tests\Feature\FeatureTestCase;

/**
 * A document keeps the tax rate it was charged at. Changing a tax rate later
 * must not rewrite documents that were issued before the change.
 */
class DocumentTaxRateTest extends FeatureTestCase
{
    public function testItStoresTheRateEachTaxWasChargedAt(): void
    {
        $tax = $this->createTax(10);

        $invoice = $this->createInvoice($tax);

        $item_tax = $invoice->item_taxes()->first();

        $this->assertEquals(10, $item_tax->rate);
        $this->assertEquals(10, $item_tax->amount);
    }

    public function testItKeepsTheChargedRateWhenTheCallerPostsIt(): void
    {
        $tax = $this->createTax(10);

        $invoice = $this->createInvoice($tax);

        $tax->update(['rate' => 25]);

        $request = $this->getRequest($tax, $invoice);
        $request['items'][0]['tax_rates'] = [$tax->id => 10];

        $this->dispatch(new UpdateDocument($invoice, $request));

        $item_tax = $invoice->refresh()->item_taxes()->first();

        $this->assertEquals(10, $item_tax->rate);
        $this->assertEquals(10, $item_tax->amount);
    }

    public function testItUsesTheCurrentRateWhenNoRateIsPosted(): void
    {
        $tax = $this->createTax(10);

        $invoice = $this->createInvoice($tax);

        $tax->update(['rate' => 25]);

        $this->dispatch(new UpdateDocument($invoice, $this->getRequest($tax, $invoice)));

        $item_tax = $invoice->refresh()->item_taxes()->first();

        $this->assertEquals(25, $item_tax->rate);
        $this->assertEquals(25, $item_tax->amount);
    }

    public function testItUsesTheCurrentRateForATaxTheDocumentDidNotHave(): void
    {
        $tax = $this->createTax(10);

        $invoice = $this->createInvoice($tax);

        $other = $this->createTax(30);

        $request = $this->getRequest($tax, $invoice);
        $request['items'][0]['tax_ids'] = [$other->id];

        $this->dispatch(new UpdateDocument($invoice, $request));

        $item_tax = $invoice->refresh()->item_taxes()->first();

        $this->assertEquals($other->id, $item_tax->tax_id);
        $this->assertEquals(30, $item_tax->rate);
        $this->assertEquals(30, $item_tax->amount);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function createTax(float $rate): Tax
    {
        return Tax::factory()->enabled()->create([
            'rate' => $rate,
            'type' => 'normal',
        ]);
    }

    private function createInvoice(Tax $tax): Document
    {
        return $this->dispatch(new CreateDocument($this->getRequest($tax)));
    }

    /**
     * One line of 100 at quantity 1, so a normal tax rate reads straight off
     * the stored amount.
     */
    private function getRequest(Tax $tax, ?Document $invoice = null): array
    {
        $request = Document::factory()->invoice()->items()->raw();

        $request['items'][0]['tax_ids'] = [$tax->id];
        $request['items'][0]['quantity'] = 1;
        $request['items'][0]['price'] = 100;

        // Changing the contact of an issued document is refused by the job
        if ($invoice) {
            $request['contact_id'] = $invoice->contact_id;
        }

        return $request;
    }
}
