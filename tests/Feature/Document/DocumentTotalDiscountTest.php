<?php

namespace Tests\Feature\Document;

use App\Jobs\Document\CreateDocument;
use App\Jobs\Document\UpdateDocument;
use App\Models\Document\Document;
use App\Models\Setting\Category;
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

    public function testItKeepsAPercentageDiscountWhenInvoicesAreEditedInBulk(): void
    {
        $this->assertBulkEditKeepsThePercentageDiscount('invoice', 'sales', 'invoices');
    }

    public function testItKeepsAPercentageDiscountWhenBillsAreEditedInBulk(): void
    {
        $this->assertBulkEditKeepsThePercentageDiscount('bill', 'purchases', 'bills');
    }

    public function testItKeepsTheDiscountOfAnInvoiceWithoutAStoredRateWhenEditedInBulk(): void
    {
        $tax = $this->createTax(10, 'normal');

        $invoice = $this->createInvoice($tax, 200, 20, 'fixed');

        // Documents saved before the rate and type columns existed have neither
        $invoice->update(['discount_rate' => null, 'discount_type' => null]);

        $this->editInBulk($invoice, 'sales', 'invoices');

        // The bulk edit does not work the document out again, so nothing about its discount moves
        $this->assertTotals($invoice, 198, 20, 18);
        $this->assertNull($invoice->getRawOriginal('discount_rate'));
        $this->assertNull($invoice->getRawOriginal('discount_type'));
    }

    public function testItKeepsAFixedDiscountWhenInvoicesAreEditedInBulk(): void
    {
        $tax = $this->createTax(10, 'normal');

        $invoice = $this->createInvoice($tax, 200, 20, 'fixed');

        $this->editInBulk($invoice, 'sales', 'invoices');

        $this->assertTotals($invoice, 198, 20, 18);
        $this->assertEquals(20, $invoice->discount_rate);
        $this->assertEquals('fixed', $invoice->discount_type);
    }

    public function testItAddsNoDiscountWhenInvoicesWithoutOneAreEditedInBulk(): void
    {
        $tax = $this->createTax(10, 'normal');

        // The edit form sends a discount of 0 as a percentage when there is none
        $invoice = $this->createInvoice($tax, 200, 0, 'percentage');

        $this->editInBulk($invoice, 'sales', 'invoices');

        $this->assertTotals($invoice, 220, 0, 20);
        $this->assertCount(0, $invoice->totals->where('code', 'discount'));
    }

    /**
     * The API takes a discount without a type, and it is worked out as an amount, while
     * the model reads the empty type as a percentage.
     */
    public function testItKeepsADiscountStoredWithoutATypeWhenEditedInBulk(): void
    {
        $tax = $this->createTax(10, 'normal');

        $request = $this->getRequest($tax, 200, 20, 'percentage');
        unset($request['discount_type']);

        $invoice = $this->dispatch(new CreateDocument($request));

        $this->assertTotals($invoice, 198, 20, 18);

        $this->editInBulk($invoice, 'sales', 'invoices');

        $this->assertTotals($invoice, 198, 20, 18);
    }

    /**
     * A bulk edit used to store the discount amount as the rate. A document left like
     * that keeps its total: changing its category is no reason to work it out again.
     */
    public function testItKeepsTheTotalOfADocumentWhoseRateWasChangedEarlierWhenEditedInBulk(): void
    {
        $tax = $this->createTax(10, 'normal');

        $invoice = $this->createInvoice($tax, 200, 10, 'percentage');

        // What an earlier bulk edit left behind: the amount stored as the rate
        $invoice->update(['discount_rate' => 20]);

        $this->editInBulk($invoice, 'sales', 'invoices');

        $this->assertTotals($invoice, 198, 20, 18);
    }

    public function testItEditsSeveralDocumentsInBulkWithoutChangingTheirTotals(): void
    {
        $tax = $this->createTax(10, 'normal');

        $percentage = $this->createInvoice($tax, 200, 10, 'percentage');
        $fixed = $this->createInvoice($tax, 200, 20, 'fixed');
        $none = $this->createInvoice($tax, 200, 0, 'percentage');

        $category = Category::factory()->income()->enabled()->create();

        $this->loginAs()
            ->post(route('bulk-actions.action', ['group' => 'sales', 'type' => 'invoices']), [
                'handle' => 'update',
                'selected' => [$percentage->id, $fixed->id, $none->id],
                'contact_id' => '',
                'category_id' => $category->id,
                'currency_code' => '',
            ])
            ->assertStatus(200);

        $this->assertTotals($percentage, 198, 20, 18);
        $this->assertTotals($fixed, 198, 20, 18);
        $this->assertTotals($none, 220, 0, 20);

        $this->assertEquals($category->id, $percentage->category_id);
        $this->assertEquals($category->id, $fixed->category_id);
        $this->assertEquals($category->id, $none->category_id);
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function assertBulkEditKeepsThePercentageDiscount(string $type, string $group, string $page): void
    {
        // 200 less 10% is 180, plus 10% tax is 198
        $tax = $this->createTax(10, 'normal');

        $request = $this->getRequest($tax, 200, 10, 'percentage', $type);

        $document = $this->dispatch(new CreateDocument($request));

        $this->editInBulk($document, $group, $page);

        $document->refresh();

        $this->assertEquals(10, $document->discount_rate);
        $this->assertEquals('percentage', $document->discount_type);
        $this->assertTotals($document, 198, 20, 18);

        // The edit form sends back the stored rate and type, as on the next save
        $request['discount'] = $document->discount_rate;
        $request['discount_type'] = $document->discount_type;
        $request['contact_id'] = $document->contact_id;

        $this->dispatch(new UpdateDocument($document, $request));

        $this->assertTotals($document, 198, 20, 18);
    }

    /**
     * What the bulk edit modal posts when only a category is picked.
     */
    private function editInBulk(Document $document, string $group, string $page): void
    {
        $this->loginAs()
            ->post(route('bulk-actions.action', ['group' => $group, 'type' => $page]), [
                'handle' => 'update',
                'selected' => [$document->id],
                'contact_id' => '',
                'category_id' => $document->category_id,
                'currency_code' => '',
            ])
            ->assertStatus(200);
    }

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
    private function getRequest(
        Tax $tax,
        float $price,
        float $discount,
        string $discount_type,
        string $type = 'invoice'
    ): array {
        $request = Document::factory()->$type()->items()->raw();

        $request['items'][0]['tax_ids'] = [$tax->id];
        $request['items'][0]['quantity'] = 1;
        $request['items'][0]['price'] = $price;

        $request['discount'] = $discount;
        $request['discount_type'] = $discount_type;

        return $request;
    }
}
