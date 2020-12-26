<?php

namespace Tests\Feature\Purchases;

use App\Jobs\Common\CreateContact;
use App\Models\Common\Contact;
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

        $request['email'] = $this->faker->safeEmail;

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

    public function getRequest()
    {
        return Contact::factory()->vendor()->enabled()->raw();
    }
}
