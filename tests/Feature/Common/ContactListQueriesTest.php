<?php

namespace Tests\Feature\Common;

use App\Jobs\Document\CreateDocument;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Models\Setting\Tax;
use App\Utilities\Date;
use Illuminate\Support\Facades\DB;
use Tests\Feature\FeatureTestCase;

/**
 * The customer and vendor lists answer two kinds of request.
 *
 * The list page needs the invoices / bills of each contact for the open and overdue
 * amounts. The JSON callers, the contact card on the document form and the search bar
 * filters, read only the contact itself. The documents went into the JSON with their
 * totals, and every total's title ran its own queries (Sentry CLOUD-PROD-LARAVEL-1V3Y,
 * an N+1 on the customer list since 2026-09).
 */
class ContactListQueriesTest extends FeatureTestCase
{
    public function testTheCustomerListJsonShouldNotLoadTheInvoices(): void
    {
        $this->assertJsonWithoutDocuments('customer', 'invoice', 'customers.index', 'invoices');
    }

    public function testTheVendorListJsonShouldNotLoadTheBills(): void
    {
        $this->assertJsonWithoutDocuments('vendor', 'bill', 'vendors.index', 'bills');
    }

    public function testTheCustomerListPageShouldShowTheOpenAndOverdueAmounts(): void
    {
        $this->assertListAmounts('customer', 'invoice', 'sent', 'customers.index');
    }

    public function testTheVendorListPageShouldShowTheOpenAndOverdueAmounts(): void
    {
        $this->assertListAmounts('vendor', 'bill', 'received', 'vendors.index');
    }

    protected function assertJsonWithoutDocuments(string $contact_type, string $document_type, string $route, string $relation): void
    {
        $this->loginAs();

        $contact = Contact::factory()->$contact_type()->enabled()->create();

        foreach (['-20 days', '+20 days', '+40 days'] as $due) {
            $this->createDocument($document_type, $contact, 'draft', 100, $due);
        }

        $queries = [];

        DB::listen(function ($query) use (&$queries) {
            foreach (['document_item_taxes', 'document_totals'] as $table) {
                if (str_contains($query->sql, $table)) {
                    $queries[] = $query->sql;
                }
            }
        });

        $response = $this->getJson(route($route))->assertOk();

        $this->assertSame([], $queries);

        $item = collect($response->json('data'))->firstWhere('id', $contact->id);

        $this->assertNotNull($item);
        $this->assertArrayNotHasKey($relation, $item);

        // What the contact card and the search filters read is still there
        $this->assertSame($contact->name, $item['name']);
        $this->assertSame($contact->email, $item['email']);
        $this->assertArrayHasKey('logo', $item);
    }

    protected function assertListAmounts(string $contact_type, string $document_type, string $status, string $route): void
    {
        $this->loginAs();

        $contact = Contact::factory()->$contact_type()->enabled()->create();

        $overdue = $this->createDocument($document_type, $contact, $status, 100, '-20 days');
        $open = $this->createDocument($document_type, $contact, $status, 70, '+20 days');

        $this->assertGreaterThan(0, $overdue->amount);
        $this->assertGreaterThan(0, $open->amount);

        $this->get(route($route))
            ->assertOk()
            ->assertSee(money($overdue->amount)->format())
            ->assertSee(money($open->amount)->format());
    }

    /**
     * A document with a tax and a discount, so it has the totals whose titles query.
     */
    protected function createDocument(string $type, Contact $contact, string $status, float $price, string $due): Document
    {
        $tax = Tax::factory()->normal()->enabled()->create();

        $request = Document::factory()->$type()->raw([
            'contact_id' => $contact->id,
            'contact_name' => $contact->name,
            'contact_email' => $contact->email,
            'currency_code' => default_currency(),
            'currency_rate' => 1,
            'issued_at' => Date::now()->subDays(30)->format('Y-m-d H:i:s'),
            'due_at' => Date::now()->modify($due)->format('Y-m-d H:i:s'),
            'status' => $status,
            'discount' => 10,
            'discount_type' => 'percentage',
        ]);

        $request['items'] = [
            [
                'type' => $type,
                'name' => 'Item',
                'description' => 'Item',
                'quantity' => '1',
                'price' => $price,
                'currency' => default_currency(),
                'tax_ids' => [$tax->id],
            ],
        ];

        $document = $this->dispatch(new CreateDocument($request));

        $this->assertSame(1, $document->totals()->where('code', 'tax')->count());
        $this->assertSame(1, $document->totals()->where('code', 'discount')->count());

        return $document;
    }
}
