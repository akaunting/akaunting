<?php

namespace Tests\Feature\Document;

use App\Jobs\Document\CreateDocument;
use App\Models\Document\Document;
use App\Models\Setting\Tax;
use Tests\Feature\FeatureTestCase;

/**
 * A tax total stores only the tax name, and two taxes can share a name. Each total
 * must still show the rate of its own tax, not the rate of the first tax found
 * with that name.
 */
class DocumentTaxTitleTest extends FeatureTestCase
{
    public function testItShowsTheRateOfEachTaxWithTheSameName(): void
    {
        $ten = $this->createTax(10);
        $twenty = $this->createTax(20);

        $invoice = $this->createInvoice([[$ten->id, $twenty->id]]);

        $this->assertEquals(['VAT (10%)', 'VAT (20%)'], $this->getTaxTitles($invoice));
    }

    public function testItFollowsTheOrderTheTaxesAppearOnTheLines(): void
    {
        $ten = $this->createTax(10);
        $twenty = $this->createTax(20);

        // The newer tax comes first on the lines, so its total is written first
        $invoice = $this->createInvoice([[$twenty->id], [$ten->id]]);

        $this->assertEquals(['VAT (20%)', 'VAT (10%)'], $this->getTaxTitles($invoice));
    }

    public function testItShowsAFixedTaxWithTheSameNameWithoutAPercentSign(): void
    {
        $normal = $this->createTax(10);
        $fixed = $this->createTax(5, 'fixed');

        // A line charges its fixed taxes before its normal ones
        $invoice = $this->createInvoice([[$normal->id, $fixed->id]]);

        $this->assertEquals(['VAT (5)', 'VAT (10%)'], $this->getTaxTitles($invoice));
    }

    public function testItKeepsEachChargedRateAfterTheTaxRatesChange(): void
    {
        $ten = $this->createTax(10);
        $twenty = $this->createTax(20);

        $invoice = $this->createInvoice([[$ten->id, $twenty->id]]);

        $ten->update(['rate' => 30]);
        $twenty->update(['rate' => 40]);

        $this->assertEquals(['VAT (10%)', 'VAT (20%)'], $this->getTaxTitles($invoice));
    }

    public function testItShowsBothRatesOnTheShowPage(): void
    {
        $ten = $this->createTax(10);
        $twenty = $this->createTax(20);

        $invoice = $this->createInvoice([[$ten->id, $twenty->id]]);

        $this->loginAs()
            ->get(route('invoices.show', $invoice->id))
            ->assertStatus(200)
            // The totals rows end the title with a colon, which tells them from other places the tax shows up
            ->assertSeeInOrder(['VAT (10%):', 'VAT (20%):']);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function createTax(float $rate, string $type = 'normal'): Tax
    {
        return Tax::factory()->enabled()->create([
            'name' => 'VAT',
            'rate' => $rate,
            'type' => $type,
        ]);
    }

    /**
     * One line of 100 at quantity 1 for each list of tax ids.
     */
    private function createInvoice(array $lines): Document
    {
        $request = Document::factory()->invoice()->items()->raw();

        $line = $request['items'][0];
        $line['quantity'] = 1;
        $line['price'] = 100;

        $request['items'] = [];

        foreach ($lines as $tax_ids) {
            $request['items'][] = array_merge($line, ['tax_ids' => $tax_ids]);
        }

        return $this->dispatch(new CreateDocument($request));
    }

    private function getTaxTitles(Document $invoice): array
    {
        return $invoice->totals()
            ->code('tax')
            ->orderBy('sort_order')
            ->get()
            ->pluck('title')
            ->all();
    }
}
