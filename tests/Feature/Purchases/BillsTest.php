<?php

namespace Tests\Feature\Purchases;

use App\Jobs\Purchase\CreateBill;
use App\Models\Purchase\Bill;
use Tests\Feature\FeatureTestCase;

class BillsTest extends FeatureTestCase
{
    public function testItShouldSeeBillListPage()
    {
        $this->loginAs()
            ->get(route('bills.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.bills', 2));
    }

    public function testItShouldSeeBillCreatePage()
    {
        $this->loginAs()
            ->get(route('bills.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.bills', 1)]));
    }

    public function testItShouldCreateBill()
    {
        $this->loginAs()
            ->post(route('bills.store'), $this->getRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldCreateBillWithRecurring()
    {
        $this->loginAs()
            ->post(route('bills.store'), $this->getRequest(true))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeBillUpdatePage()
    {
        $bill = $this->dispatch(new CreateBill($this->getRequest()));

        $this->loginAs()
            ->get(route('bills.edit', $bill->id))
            ->assertStatus(200)
            ->assertSee($bill->contact_email);
    }

    public function testItShouldUpdateBill()
    {
        $request = $this->getRequest();

        $bill = $this->dispatch(new CreateBill($request));

        $request['contact_email'] = $this->faker->safeEmail;

        $this->loginAs()
            ->patch(route('bills.update', $bill->id), $request)
            ->assertStatus(200)
			->assertSee($request['contact_email']);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteBill()
    {
        $bill = $this->dispatch(new CreateBill($this->getRequest()));

        $this->loginAs()
            ->delete(route('bills.destroy', $bill->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function getRequest($recurring = false)
    {
        $factory = factory(Bill::class);

        $recurring ? $factory->states('items', 'recurring') : $factory->states('items');

        return $factory->raw();
    }
}
