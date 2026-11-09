<?php

namespace Tests\Feature\Common;

use App\Jobs\Document\CreateDocument;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Utilities\Date;
use App\View\Components\Contacts\Show\Content;
use Tests\Feature\FeatureTestCase;

/**
 * The open and overdue amounts at the top of the customer and vendor pages.
 *
 * A draft has not been sent or received yet, so nothing is owed on it. The contact
 * lists already leave drafts out of the same amounts (Contact::getOpenAttribute,
 * Contact::getOverdueAttribute), and the page has to leave them out as well.
 */
class ContactSummaryTest extends FeatureTestCase
{
    public function testItLeavesDraftInvoicesOutOfTheCustomerSummary(): void
    {
        $customer = Contact::factory()->customer()->enabled()->create();

        $this->createDocument('invoice', $customer, 'draft', 100, '-20 days');
        $this->createDocument('invoice', $customer, 'draft', 70, '+20 days');
        $this->createDocument('invoice', $customer, 'sent', 40, '-20 days');
        $this->createDocument('invoice', $customer, 'sent', 30, '+20 days');

        $this->assertSummary($customer, 'customer', 40, 30);
    }

    public function testItLeavesDraftBillsOutOfTheVendorSummary(): void
    {
        $vendor = Contact::factory()->vendor()->enabled()->create();

        $this->createDocument('bill', $vendor, 'draft', 100, '-20 days');
        $this->createDocument('bill', $vendor, 'draft', 70, '+20 days');
        $this->createDocument('bill', $vendor, 'received', 40, '-20 days');
        $this->createDocument('bill', $vendor, 'received', 30, '+20 days');

        $this->assertSummary($vendor, 'vendor', 40, 30);
    }

    protected function assertSummary(Contact $contact, string $type, float $overdue, float $open): void
    {
        $this->loginAs();

        $component = new Content($type, $contact->fresh());
        $component->render();

        $this->assertSame(money($overdue)->format(), $component->summary_amounts['overdue_exact']);
        $this->assertSame(money($open)->format(), $component->summary_amounts['open_exact']);

        // The same amounts as in the list of contacts
        $contact = $contact->fresh();

        $this->assertEquals($overdue, $contact->overdue);
        $this->assertEquals($open, $contact->open);
    }

    protected function createDocument(string $type, Contact $contact, string $status, float $price, string $due): Document
    {
        $issued_at = Date::now()->subDays(30)->format('Y-m-d H:i:s');
        $due_at = Date::now()->modify($due)->format('Y-m-d H:i:s');

        $request = Document::factory()->$type()->raw([
            'contact_id' => $contact->id,
            'contact_name' => $contact->name,
            'contact_email' => $contact->email,
            'currency_code' => default_currency(),
            'currency_rate' => 1,
            'issued_at' => $issued_at,
            'due_at' => $due_at,
            'status' => $status,
        ]);

        $request['items'] = [
            [
                'type' => $type,
                'name' => 'Item',
                'description' => 'Item',
                'quantity' => '1',
                'price' => $price,
                'currency' => default_currency(),
            ],
        ];

        return $this->dispatch(new CreateDocument($request));
    }
}
