<?php

namespace Tests\Feature\Document;

use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\UpdateDocument;
use App\Listeners\Update\V33\Version331;
use App\Models\Document\Document;
use App\Models\Setting\Tax;
use Tests\Feature\FeatureTestCase;

/**
 * Documents saved before 3.1.15 have no discount rate or type, and the API accepts a discount
 * without a type. The 3.3.1 update stores what they were worked out with, so opening one in
 * the form and saving it keeps its discount.
 */
class DocumentLegacyDiscountTest extends FeatureTestCase
{
    public function testItStoresAPercentageDiscountOfADocumentSavedWithoutARate(): void
    {
        $tax = $this->createTax(10);

        $invoice = $this->createInvoice($tax, 200, 10, 'percentage');
        $this->makeLegacy($invoice, ['discount_rate' => null, 'discount_type' => null]);

        $this->runUpdate();

        $this->assertDiscount($invoice, 'percentage', 10);
        $this->assertSavingFromTheFormKeepsTheTotals($invoice, $tax, 200, [198, 20, 18]);
    }

    /**
     * 15.50 of 200 is 7.75%, which comes to the same discount, so it is stored as that.
     */
    public function testItStoresAFixedDiscountThatComesToAPercentageAsThatPercentage(): void
    {
        $tax = $this->createTax(10);

        $invoice = $this->createInvoice($tax, 200, 15.5, 'fixed');
        $this->makeLegacy($invoice, ['discount_rate' => null, 'discount_type' => null]);

        $this->runUpdate();

        $this->assertDiscount($invoice, 'percentage', 7.75);
        $this->assertSavingFromTheFormKeepsTheTotals($invoice, $tax, 200, [202.95, 15.5, 18.45]);
    }

    /**
     * 13.33 of 200 is 6.665%, which no percentage of two decimals comes to, so it stays an amount.
     */
    public function testItStoresAFixedDiscountThatNoPercentageComesToAsAnAmount(): void
    {
        $tax = $this->createTax(10);

        $invoice = $this->createInvoice($tax, 200, 13.33, 'fixed');
        $this->makeLegacy($invoice, ['discount_rate' => null, 'discount_type' => null]);

        $this->runUpdate();

        $this->assertDiscount($invoice, 'fixed', 13.33);
        $this->assertSavingFromTheFormKeepsTheTotals($invoice, $tax, 200, [205.34, 13.33, 18.67]);
    }

    public function testItStoresTheTypeOfADiscountSavedWithoutOne(): void
    {
        $tax = $this->createTax(10);

        $invoice = $this->createInvoice($tax, 200, 20, 'fixed');
        $this->makeLegacy($invoice, ['discount_type' => null]);

        $this->runUpdate();

        $this->assertDiscount($invoice, 'fixed', 20);
        $this->assertSavingFromTheFormKeepsTheTotals($invoice, $tax, 200, [198, 20, 18]);
    }

    public function testItLeavesADocumentSavedWithoutADiscount(): void
    {
        $tax = $this->createTax(10);

        $invoice = $this->createInvoice($tax, 200, 0, 'percentage');
        $this->makeLegacy($invoice, ['discount_rate' => null, 'discount_type' => null]);

        $this->runUpdate();

        $this->assertNull($invoice->refresh()->getRawOriginal('discount_rate'));
        $this->assertNull($invoice->getRawOriginal('discount_type'));
        $this->assertSavingFromTheFormKeepsTheTotals($invoice, $tax, 200, [220, 0, 20]);
    }

    public function testItLeavesTheDiscountOfACurrentDocument(): void
    {
        $tax = $this->createTax(10);

        $percentage = $this->createInvoice($tax, 200, 10, 'percentage');
        $fixed = $this->createInvoice($tax, 200, 15.5, 'fixed');

        $this->runUpdate();

        $this->assertDiscount($percentage, 'percentage', 10);
        $this->assertDiscount($fixed, 'fixed', 15.5);
    }

    /**
     * The total works a discount without a type out as an amount, so it is stored as one.
     */
    public function testItStoresADiscountSentWithoutATypeAsAnAmount(): void
    {
        $tax = $this->createTax(10);

        $request = $this->getRequest($tax, 200, 20, 'fixed');
        unset($request['discount_type']);

        $invoice = $this->dispatch(new CreateDocument($request));

        $this->assertDiscount($invoice, 'fixed', 20);
        $this->assertTotals($invoice, 198, 20, 18);

        $request = $this->getRequest($tax, 200, 30, 'fixed');
        unset($request['discount_type']);

        $this->dispatch(new UpdateDocument($invoice->refresh(), $request));

        $this->assertDiscount($invoice, 'fixed', 30);
        $this->assertTotals($invoice, 187, 30, 17);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function runUpdate(): void
    {
        $listener = new Version331();

        $listener->updateDocumentDiscounts();
        $listener->clearCache();
    }

    /**
     * What a document saved before 3.1.15, or through the API without a type, looks like.
     */
    private function makeLegacy(Document $invoice, array $columns): void
    {
        $invoice->update($columns);
    }

    private function assertDiscount(Document $invoice, string $type, float $rate): void
    {
        $invoice->refresh();

        $this->assertSame($type, $invoice->getRawOriginal('discount_type'));
        $this->assertEquals($rate, $invoice->getRawOriginal('discount_rate'));
    }

    /**
     * Checks the totals, opens the edit form, saves the document with the discount the form
     * starts with, the way it posts it when nothing else is touched, and checks them again.
     */
    private function assertSavingFromTheFormKeepsTheTotals(Document $invoice, Tax $tax, float $price, array $totals): void
    {
        $this->assertTotals($invoice, ...$totals);

        $html = $this->loginAs()
            ->get(route('invoices.edit', $invoice->id))
            ->assertStatus(200)
            ->getContent();

        $discount = $this->hiddenValue($html, 'discount');
        $discount_type = $this->hiddenValue($html, 'discount_type');

        $this->dispatch(new UpdateDocument($invoice->refresh(), $this->getRequest($tax, $price, (float) $discount, $discount_type)));

        $this->assertTotals($invoice, ...$totals);
    }

    private function hiddenValue(string $html, string $name): string
    {
        $pattern = '/<input type="hidden"\s+name="' . $name . '"\s+id="[^"]*"\s+value="([^"]*)"/';

        $this->assertMatchesRegularExpression($pattern, $html);

        preg_match($pattern, $html, $matches);

        return $matches[1];
    }

    private function assertTotals(Document $invoice, float $total, float $discount, float $tax): void
    {
        $totals = $invoice->refresh()->totals;

        $this->assertEquals($total, $invoice->amount);
        $this->assertEquals($total, $totals->where('code', 'total')->sum('amount'));
        $this->assertEquals($discount, $totals->where('code', 'discount')->sum('amount'));
        $this->assertEquals($tax, $totals->where('code', 'tax')->sum('amount'));
    }

    private function createTax(float $rate): Tax
    {
        return Tax::factory()->enabled()->create([
            'rate' => $rate,
            'type' => 'normal',
        ]);
    }

    private function createInvoice(Tax $tax, float $price, float $discount, string $discount_type): Document
    {
        return $this->dispatch(new CreateDocument($this->getRequest($tax, $price, $discount, $discount_type)));
    }

    /**
     * One line at quantity 1 and a discount on the whole document.
     */
    private function getRequest(Tax $tax, float $price, float $discount, string $discount_type): array
    {
        $request = Document::factory()->invoice()->items()->raw();

        $request['items'][0]['tax_ids'] = [$tax->id];
        $request['items'][0]['quantity'] = 1;
        $request['items'][0]['price'] = $price;

        $request['discount'] = $discount;
        $request['discount_type'] = $discount_type;

        return $request;
    }
}
