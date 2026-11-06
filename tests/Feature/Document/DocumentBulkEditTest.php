<?php

namespace Tests\Feature\Document;

use App\Jobs\Document\CreateDocument;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use Tests\Feature\FeatureTestCase;

/**
 * What the bulk edit of invoices and bills changes: the contact, the category and the
 * currency the modal picked, and nothing else.
 *
 * What it does to the discount and the totals is in DocumentTotalDiscountTest.
 */
class DocumentBulkEditTest extends FeatureTestCase
{
    /**
     * A document keeps its own copy of the contact, which is what it prints. Changing
     * the contact has to change that copy, or the invoice goes out with the old name.
     */
    public function testItCopiesTheNewContactOntoTheDocument(): void
    {
        $invoice = $this->createDocument('invoice', 'draft');

        $contact = Contact::factory()->customer()->enabled()->create([
            'name' => 'New Customer Ltd',
            'email' => 'billing@new-customer.test',
            'tax_number' => '1234567890',
            'phone' => '+90 212 000 00 00',
            'address' => 'Main Street 1',
            'city' => 'Istanbul',
            'state' => 'Istanbul',
            'zip_code' => '34000',
            'country' => 'TR',
        ]);

        $this->editInBulk([$invoice], 'sales', 'invoices', ['contact_id' => $contact->id]);

        $invoice->refresh();

        $this->assertEquals($contact->id, $invoice->contact_id);
        $this->assertSame('New Customer Ltd', $invoice->contact_name);
        $this->assertSame('billing@new-customer.test', $invoice->contact_email);
        $this->assertSame('1234567890', $invoice->contact_tax_number);
        $this->assertSame('+90 212 000 00 00', $invoice->contact_phone);
        $this->assertSame('Main Street 1', $invoice->contact_address);
        $this->assertSame('Istanbul', $invoice->contact_city);
        $this->assertSame('Istanbul', $invoice->contact_state);
        $this->assertSame('34000', $invoice->contact_zip_code);
        $this->assertSame('TR', $invoice->contact_country);
    }

    /**
     * Empty fields of the request are dropped before the update, so a contact without an
     * email could leave the email of the one it replaces on the document.
     */
    public function testItClearsWhatTheNewContactDoesNotHave(): void
    {
        $invoice = $this->createDocument('invoice', 'draft');

        $this->assertNotEmpty($invoice->contact_email);

        $contact = Contact::factory()->customer()->enabled()->create([
            'email' => null,
            'tax_number' => null,
        ]);

        $this->editInBulk([$invoice], 'sales', 'invoices', ['contact_id' => $contact->id]);

        $invoice->refresh();

        $this->assertEquals($contact->id, $invoice->contact_id);
        $this->assertNull($invoice->contact_email);
        $this->assertNull($invoice->contact_tax_number);
    }

    public function testItCopiesTheNewVendorOntoABill(): void
    {
        $bill = $this->createDocument('bill', 'draft');

        $vendor = Contact::factory()->vendor()->enabled()->create(['name' => 'New Vendor Ltd']);

        $this->editInBulk([$bill], 'purchases', 'bills', ['contact_id' => $vendor->id]);

        $bill->refresh();

        $this->assertEquals($vendor->id, $bill->contact_id);
        $this->assertSame('New Vendor Ltd', $bill->contact_name);
    }

    /**
     * UpdateDocument refuses a new contact on a document that has gone out, and the copy
     * of the contact is not touched either.
     */
    public function testItLeavesTheContactOfASentInvoice(): void
    {
        $invoice = $this->createDocument('invoice', 'sent');

        $contact = Contact::factory()->customer()->enabled()->create(['name' => 'New Customer Ltd']);

        $this->editInBulk([$invoice], 'sales', 'invoices', ['contact_id' => $contact->id]);

        $this->assertFlashLevel('danger');

        $fresh = $invoice->fresh();

        $this->assertEquals($invoice->contact_id, $fresh->contact_id);
        $this->assertSame($invoice->contact_name, $fresh->contact_name);
    }

    /**
     * Reports convert every document at its own rate, so a new currency needs its rate.
     */
    public function testItTakesTheRateOfTheNewCurrency(): void
    {
        $invoice = $this->createDocument('invoice', 'draft');

        $currency = Currency::factory()->create(['rate' => 2.5, 'enabled' => 1]);

        $this->assertNotEquals($currency->code, $invoice->currency_code);

        $this->editInBulk([$invoice], 'sales', 'invoices', ['currency_code' => $currency->code]);

        $fresh = $invoice->fresh();

        $this->assertSame($currency->code, $fresh->currency_code);
        $this->assertEquals(2.5, $fresh->currency_rate);

        // The amounts are not converted, the same as before: only the currency they are in changes
        $this->assertEquals($invoice->amount, $fresh->amount);
    }

    /**
     * The same request is reused for every selected document. When no category was
     * picked, the second document's category used to stay in it and was given to every
     * document after it.
     */
    public function testItKeepsEachDocumentsCategoryWhenNoneIsPicked(): void
    {
        $invoices = [];

        foreach (range(1, 3) as $i) {
            $invoice = $this->createDocument('invoice', 'draft');

            $invoice->update(['category_id' => Category::factory()->income()->enabled()->create()->id]);

            $invoices[] = $invoice->fresh();
        }

        $this->editInBulk($invoices, 'sales', 'invoices', []);

        foreach ($invoices as $invoice) {
            $this->assertEquals($invoice->category_id, $invoice->fresh()->category_id);
        }
    }

    // -------------------------------------------------------------------------
    // Helpers
    // -------------------------------------------------------------------------

    private function createDocument(string $type, string $status): Document
    {
        $request = Document::factory()->$type()->$status()->items()->raw();

        return $this->dispatch(new CreateDocument($request));
    }

    /**
     * What the bulk edit modal posts: every field, empty where nothing was picked.
     *
     * @param array<Document> $documents
     */
    private function editInBulk(array $documents, string $group, string $page, array $picked): void
    {
        $this->loginAs()
            ->post(route('bulk-actions.action', ['group' => $group, 'type' => $page]), array_merge([
                'handle' => 'update',
                'selected' => array_map(fn ($document) => $document->id, $documents),
                'contact_id' => '',
                'category_id' => '',
                'currency_code' => '',
            ], $picked))
            ->assertStatus(200);
    }
}
