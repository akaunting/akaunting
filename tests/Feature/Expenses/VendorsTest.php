<?php

namespace Tests\Feature\Expenses;

use App\Models\Expense\Vendor;
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
            ->assertStatus(302)
            ->assertRedirect(route('vendors.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeVendorDetailPage()
    {
        $vendor = Vendor::create($this->getVendorRequest());

        $this->loginAs()
            ->get(route('vendors.show', ['vendor' => $vendor->id]))
            ->assertStatus(200)
            ->assertSee($vendor->email);
    }

    public function testItShouldSeeVendorUpdatePage()
    {
        $vendor = Vendor::create($this->getVendorRequest());

        $this->loginAs()
            ->get(route('vendors.edit', ['vendor' => $vendor->id]))
            ->assertStatus(200)
            ->assertSee($vendor->email)
            ->assertSee($vendor->name);
    }

    public function testItShouldUpdateVendor()
    {
        $request = $this->getVendorRequest();

        $vendor = Vendor::create($request);

        $request['name'] = $this->faker->name;

        $this->loginAs()
            ->patch(route('vendors.update', $vendor->id), $request)
            ->assertStatus(302)
            ->assertRedirect(route('vendors.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteVendor()
    {
        $vendor = Vendor::create($this->getVendorRequest());

        $this->loginAs()
            ->delete(route('vendors.destroy', $vendor->id))
            ->assertStatus(302)
            ->assertRedirect(route('vendors.index'));

        $this->assertFlashLevel('success');
    }

    private function getVendorRequest()
    {
        return [
            'company_id' => $this->company->id,
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
