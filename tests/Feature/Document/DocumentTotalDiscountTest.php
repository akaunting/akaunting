<?php

namespace Tests\Feature\Document;

use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\UpdateDocument;
use App\Models\Document\Document;
use App\Models\Setting\Tax;
use Tests\Feature\FeatureTestCase;

/**
 * A discount on the whole document is taken from what each line comes to, so
 * a 10% discount and a discount of the same amount give the same document.
 */
class DocumentTotalDiscountTest extends FeatureTestCase
{
    public function testItTakesTheFullPercentageDiscountFromAPriceWithInclusiveTax(): void
    {
        // 110 with 10% tax in it, less 10%, is 99 with 9 tax in it
        $tax = $this->createTax(10, 'inclusive');

        $invoice = $this->createInvoice($tax, 110, 10, 'percentage');

        $this->assertTotals($invoice, 99, 11, 9);
    }

    public function testItTakesAFixedDiscountOnceFromAPriceWithInclusiveTax(): void
    {
        $tax = $this->createTax(10, 'inclusive');

        $invoice = $this->createInvoice($tax, 110, 11, 'fixed');

        $this->assertTotals($invoice, 99, 11, 9);
    }

    public function testItTakesAPercentageDiscountBeforeANormalTax(): void
    {
        // 100 less 10% is 90, plus 10% tax is 99
        $tax = $this->createTax(10, 'normal');

        $invoice = $this->createInvoice($tax, 100, 10, 'percentage');

        $this->assertTotals($invoice, 99, 10, 9);
    }

    public function testItTakesAPercentageDiscountFromLinesWithAndWithoutInclusiveTax(): void
    {
        $inclusive = $this->createTax(10, 'inclusive');
        $normal = $this->createTax(10, 'normal');

        $request = $this->getRequest($inclusive, 110, 10, 'percentage');

        $second = $request['items'][0];
        $second['tax_ids'] = [$normal->id];
        $second['price'] = 100;

        $request['items'][] = $second;

        $invoice = $this->dispatch(new CreateDocument($request));

        // 99 for the first line as above, 90 plus 9 tax for the second
        $this->assertTotals($invoice, 198, 21, 18);
    }

    public function testItTakesTheFullPercentageDiscountWhenADocumentIsUpdated(): void
    {
        $tax = $this->createTax(10, 'inclusive');

        $invoice = $this->createInvoice($tax, 110, 10, 'percentage');

        $request = $this->getRequest($tax, 110, 10, 'percentage');

        // Changing the contact of an issued document is refused by the job
        $request['contact_id'] = $invoice->contact_id;

        $this->dispatch(new UpdateDocument($invoice, $request));

        $this->assertTotals($invoice, 99, 11, 9);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function assertTotals(Document $invoice, float $total, float $discount, float $tax): void
    {
        $totals = $invoice->refresh()->totals;

        $this->assertEquals($total, $invoice->amount);
        $this->assertEquals($total, $totals->where('code', 'total')->sum('amount'));
        $this->assertEquals($discount, $totals->where('code', 'discount')->sum('amount'));
        $this->assertEquals($tax, $totals->where('code', 'tax')->sum('amount'));
    }

    private function createTax(float $rate, string $type): Tax
    {
        return Tax::factory()->enabled()->create([
            'rate' => $rate,
            'type' => $type,
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
