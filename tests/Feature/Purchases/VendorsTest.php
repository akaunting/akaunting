<?php

namespace Tests\Feature\Purchases;

use App\Exports\Purchases\Vendors as Export;
use App\Jobs\Common\CreateContact;
use App\Models\Common\Contact;
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
