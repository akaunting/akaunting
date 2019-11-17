<?php

namespace Tests\Feature\Expenses;

use App\Jobs\Common\CreateContact;
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
            ->post(route('vendors.store'), $this->getVendorRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeVendorDetailPage()
    {
        $vendor = $this->dispatch(new CreateContact($this->getVendorRequest()));

        $this->loginAs()
            ->get(route('vendors.show', ['vendor' => $vendor->id]))
            ->assertStatus(200)
            ->assertSee($vendor->email);
    }

    public function testItShouldSeeVendorUpdatePage()
    {
        $vendor = $this->dispatch(new CreateContact($this->getVendorRequest()));

        $this->loginAs()
            ->get(route('vendors.edit', ['vendor' => $vendor->id]))
            ->assertStatus(200)
            ->assertSee($vendor->email)
            ->assertSee($vendor->name);
    }

    public function testItShouldUpdateVendor()
    {
        $request = $this->getVendorRequest();

        $vendor = $this->dispatch(new CreateContact($request));

        $request['name'] = $this->faker->name;

        $this->loginAs()
            ->patch(route('vendors.update', $vendor->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteVendor()
    {
        $vendor = $this->dispatch(new CreateContact($this->getVendorRequest()));

        $this->loginAs()
            ->delete(route('vendors.destroy', $vendor->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    private function getVendorRequest()
    {
        return [
            'company_id' => $this->company->id,
            'type' => 'vendor',
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'tax_number' => $this->faker->randomNumber(9),
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'website' => 'www.akaunting.com',
            'currency_code' => $this->company->currencies()->enabled()->first()->code,
            'enabled' => $this->faker->boolean ? 1 : 0
        ];
    }
}
