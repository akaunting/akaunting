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
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('bills.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('bills', [
            'bill_number' => $request['bill_number'],
        ]);
    }

    public function testItShouldCreateBillWithRecurring()
    {
        $request = $this->getRequest(true);

        $this->loginAs()
            ->post(route('bills.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('bills', [
            'bill_number' => $request['bill_number'],
        ]);
    }

    public function testItShouldSeeBillUpdatePage()
    {
        $request = $this->getRequest();

        $bill = $this->dispatch(new CreateBill($request));

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

        $this->assertDatabaseHas('bills', [
            'bill_number' => $request['bill_number'],
            'contact_email' => $request['contact_email'],
        ]);
    }

    public function testItShouldDeleteBill()
    {
        $request = $this->getRequest();

        $bill = $this->dispatch(new CreateBill($request));

        $this->loginAs()
            ->delete(route('bills.destroy', $bill->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('bills', [
            'bill_number' => $request['bill_number'],
        ]);
    }

    public function getRequest($recurring = false)
    {
        $factory = Bill::factory();

        $recurring ? $factory->items()->recurring() : $factory->items();

        return $factory->raw();
    }
}
