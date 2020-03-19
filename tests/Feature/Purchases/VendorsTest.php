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
        $this->loginAs()
            ->post(route('vendors.store'), $this->getRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeVendorDetailPage()
    {
        $vendor = $this->dispatch(new CreateContact($this->getRequest()));

        $this->loginAs()
            ->get(route('vendors.show', $vendor->id))
            ->assertStatus(200)
            ->assertSee($vendor->email);
    }

    public function testItShouldSeeVendorUpdatePage()
    {
        $vendor = $this->dispatch(new CreateContact($this->getRequest()));

        $this->loginAs()
            ->get(route('vendors.edit', $vendor->id))
            ->assertStatus(200)
            ->assertSee($vendor->email);
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
    }

    public function testItShouldDeleteVendor()
    {
        $vendor = $this->dispatch(new CreateContact($this->getRequest()));

        $this->loginAs()
            ->delete(route('vendors.destroy', $vendor->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return factory(Contact::class)->states('vendor', 'enabled')->raw();
    }
}
