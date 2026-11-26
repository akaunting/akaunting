<?php

namespace Tests\Feature\Purchases;

use App\Exports\Purchases\Vendors as Export;
use App\Jobs\Common\CreateContact;
use App\Jobs\Document\CreateDocument;
use App\Models\Common\Contact;
use App\Models\Document\Document;
use App\Models\Setting\Category;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Tests\Feature\FeatureTestCase;

class VendorsTest extends FeatureTestCase
{
    public function testItShouldSeeVendorListPage()
    {
        $this->loginAs()
            ->get(route('vendors.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.vendors', 2));
    }

    public function testItShouldSeeVendorShowPage()
    {
        $request = $this->getRequest();

        $vendor = $this->dispatch(new CreateContact($request));

        $this->loginAs()
            ->get(route('vendors.show', $vendor->id))
            ->assertStatus(200)
            ->assertSee($vendor->email);
    }

    public function testItShouldSeeVendorCreatePage()
    {
        $this->loginAs()
            ->get(route('vendors.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.vendors', 1)]));
    }

    public function testItShouldCreateVendor()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('vendors.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('contacts', $request);
    }

    public function testItShouldNotLinkVendorToTheUserSent()
    {
        $request = $this->getRequest();
        $request['user_id'] = $this->user->id;

        // The user of a contact is the one it creates, the one sent is not taken
        $this->loginAs()
            ->postJson(route('vendors.store'), $request)
            ->assertOk()
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('contacts', ['email' => $request['email'], 'user_id' => null]);
    }

    public function testItShouldSeeVendorDetailPage()
    {
        $request = $this->getRequest();

        $vendor = $this->dispatch(new CreateContact($request));

        $this->loginAs()
            ->get(route('vendors.show', $vendor->id))
            ->assertStatus(200)
            ->assertSee($vendor->email);
    }

    public function testItShouldSeeVendorUpdatePage()
    {
        $request = $this->getRequest();

        $vendor = $this->dispatch(new CreateContact($request));

        $this->loginAs()
            ->get(route('vendors.edit', $vendor->id))
            ->assertStatus(200)
            ->assertSee($vendor->email);

        $this->assertDatabaseHas('contacts', $request);
    }

    public function testItShouldUpdateVendor()
    {
        $request = $this->getRequest();

        $vendor = $this->dispatch(new CreateContact($request));

        $request['email'] = $this->faker->freeEmail;

        $this->loginAs()
            ->patch(route('vendors.update', $vendor->id), $request)
            ->assertStatus(200)
            ->assertSee($request['email']);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('contacts', $request);
    }

    public function testItShouldDeleteVendor()
    {
        $request = $this->getRequest();

        $vendor = $this->dispatch(new CreateContact($request));

        $this->loginAs()
            ->delete(route('vendors.destroy', $vendor->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('contacts', $request);
    }

    public function testItShouldNotDeleteVendorUsedByRecurringBill()
    {
        $vendor = $this->createVendorUsedByRecurringBill();

        $this->loginAs()
            ->delete(route('vendors.destroy', $vendor->id))
            ->assertStatus(200);

        $this->assertFlashLevel('danger');

        $this->assertNotSoftDeleted($vendor);
    }

    public function testItShouldNotDisableVendorUsedByRecurringBill()
    {
        $vendor = $this->createVendorUsedByRecurringBill();

        $response = $this->loginAs()
            ->get(route('vendors.disable', $vendor->id))
            ->assertStatus(200)
            ->assertJson(['success' => false]);

        $this->assertStringContainsString('recurring bill', $response->json('message'));

        $this->assertEquals(1, $vendor->fresh()->enabled);
    }

    public function createVendorUsedByRecurringBill(): Contact
    {
        $vendor = $this->dispatch(new CreateContact($this->getRequest()));

        $request = Document::factory()->bill()->items()->recurring()->raw();
        $request['contact_id'] = $vendor->id;
        $request['contact_name'] = $vendor->name;

        $this->dispatch(new CreateDocument($request));

        return $vendor;
    }

    public function testItShouldExportVendors()
    {
        Contact::factory()->vendor()->count(5)->create();
        $count = Contact::vendor()->count();

        Excel::fake();

        $this->loginAs()
            ->get(route('vendors.export'))
            ->assertStatus(200);

        Excel::matchByRegex();

        Excel::assertDownloaded(
            '/' . str()->filename(trans_choice('general.vendors', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($count) {
                // Assert that the correct export is downloaded.
                return $export->collection()->count() === $count;
            }
        );
    }

    public function testItShouldExportSelectedVendors()
    {
        $create_count = 5;
        $select_count = 3;

        $vendors = Contact::factory()->vendor()->count($create_count)->create();

        Excel::fake();

        $this->loginAs()
            ->post(
                route('bulk-actions.action', ['group' => 'purchases', 'type' => 'vendors']),
                ['handle' => 'export', 'selected' => $vendors->take($select_count)->pluck('id')->toArray()]
            )
            ->assertStatus(200);

        Excel::matchByRegex();

        Excel::assertDownloaded(
            '/' . str()->filename(trans_choice('general.vendors', 2)) . '-\d{10}\.xlsx/',
            function (Export $export) use ($select_count) {
                return $export->collection()->count() === $select_count;
            }
        );
    }

    public function testItShouldNotExportTheNotAvailableCategoryOfAVendorWithNone()
    {
        // The category relation falls back to an "N/A" category; written to the file, the
        // import opened an expense category of that name and put every such vendor in it
        $category = Category::factory()->expense()->create();

        $without = Contact::factory()->vendor()->create(['category_id' => null]);
        $with = Contact::factory()->vendor()->create(['category_id' => $category->id]);

        $export = new Export([$without->id, $with->id]);

        $rows = $export->collection()->keyBy('id')->map(fn ($vendor) => array_combine($export->fields, $export->map($vendor)))->all();

        $this->assertNull($rows[$without->id]['category_name']);
        $this->assertSame($category->name, $rows[$with->id]['category_name']);
    }

    public function testItShouldKeepContactPersonsAndRecurringBillsOnBulkUpdate()
    {
        $vendor = $this->dispatch(new CreateContact(array_merge($this->getRequest(), [
            'contact_persons' => [
                ['name' => 'Jane Doe', 'email' => 'jane@example.com', 'phone' => ''],
            ],
        ])));

        $bill = Document::create(array_merge(Document::factory()->bill()->raw(), [
            'type' => Document::BILL_RECURRING_TYPE,
            'contact_id' => $vendor->id,
            'contact_name' => $vendor->name,
            'contact_email' => $vendor->email,
            'contact_phone' => $vendor->phone,
        ]));

        $this->loginAs()
            ->post(route('bulk-actions.action', ['group' => 'purchases', 'type' => 'vendors']), [
                'handle' => 'update',
                'selected' => [$vendor->id],
                'currency_code' => '',
                'country' => 'TR',
            ])
            ->assertStatus(200);

        $this->assertEquals('TR', $vendor->fresh()->country);
        $this->assertEquals(['Jane Doe'], $vendor->fresh()->contact_persons->pluck('name')->all());

        $bill = $bill->fresh();

        $this->assertEquals($vendor->name, $bill->contact_name);
        $this->assertEquals($vendor->email, $bill->contact_email);
        $this->assertEquals($vendor->phone, $bill->contact_phone);
        $this->assertEquals('TR', $bill->contact_country);
    }

    public function testItShouldImportVendors()
    {
        Excel::fake();

        $this->loginAs()
            ->post(
                route('vendors.import'),
                [
                    'import' => UploadedFile::fake()->createWithContent(
                        'vendors.xlsx',
                        File::get(public_path('files/import/vendors.xlsx'))
                    ),
                ]
            )
            ->assertStatus(200);

        Excel::assertImported('vendors.xlsx');

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return Contact::factory()->vendor()->enabled()->raw();
    }
}
