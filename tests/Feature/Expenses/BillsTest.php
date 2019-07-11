<?php

namespace Tests\Feature\Expenses;

use App\Models\Common\Item;
use App\Models\Expense\Bill;
use App\Models\Expense\Vendor;
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
            ->post(route('bills.store'), $this->getBillRequest())
            ->assertStatus(302)
            ->assertRedirect(route('bills.show', ['bill' => 1]));

        $this->assertFlashLevel('success');
    }

    public function testItShouldCreateBillWithRecurring()
    {
        $this->loginAs()
            ->post(route('bills.store'), $this->getBillRequest(1))
            ->assertStatus(302)
            ->assertRedirect(route('bills.show', ['bill' => 1]));

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeBillUpdatePage()
    {
        $bill = Bill::create($this->getBillRequest());
        $this->loginAs()
            ->get(route('bills.edit', ['bill' => $bill->id]))
            ->assertStatus(200)
            ->assertSee($bill->vendor_name)
            ->assertSee($bill->vendor_email);
    }

    public function testItShouldUpdateBill()
    {
        $request = $this->getBillRequest();

        $bill = Bill::create($request);

        $request['vendor_name'] = $this->faker->name;

        $this->loginAs()
            ->patch(route('bills.update', $bill->id), $request)
            ->assertStatus(302);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteBill()
    {
        $bill = Bill::create($this->getBillRequest());

        $this->loginAs()
            ->delete(route('bills.destroy', $bill->id))
            ->assertStatus(302)
            ->assertRedirect(route('bills.index'));

        $this->assertFlashLevel('success');

    }

    private function getBillRequest($recurring = 0)
    {
        $amount = $this->faker->randomFloat(2, 2);

        $items = [['name' =>  $this->faker->text(5), 'item_id' => null, 'quantity' => '1', 'price' => $amount, 'currency' => 'USD', 'tax_id' => null]];

        $data =  [
            'vendor_id' => '0',
            'billed_at' => $this->faker->date(),
            'due_at' => $this->faker->date(),
            'bill_number' => '1',
            'order_number' => '1',
            'currency_code' => setting('general.default_currency'),
            'currency_rate' => '1',
            'item' => $items,
            'discount' => '0',
            'notes' => $this->faker->text(5),
            'category_id' => $this->company->categories()->type('expense')->first()->id,
            'recurring_frequency' => 'no',
            'vendor_name' =>  $this->faker->name,
            'vendor_email' =>$this->faker->email,
            'vendor_tax_number' => null,
            'vendor_phone' =>  null,
            'vendor_address' =>  $this->faker->address,
            'bill_status_code' => 'draft',
            'amount' => $amount,
            'company_id' => $this->company->id,
        ];

        if ($recurring) {
            $data['recurring_frequency'] = 'yes';
            $data['recurring_interval'] = '1';
            $data['recurring_custom_frequency'] = $this->faker->randomElement(['monthly', 'weekly']);
            $data['recurring_count'] = '1';
        }

        return $data;
    }
}
