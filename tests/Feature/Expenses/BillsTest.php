<?php

namespace Tests\Feature\Expenses;

use App\Jobs\Expense\CreateBill;
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
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldCreateBillWithRecurring()
    {
        $this->loginAs()
            ->post(route('bills.store'), $this->getBillRequest(true))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeBillUpdatePage()
    {
        $bill = $this->dispatch(new CreateBill($this->getBillRequest()));

        $this->loginAs()
            ->get(route('bills.edit', ['bill' => $bill->id]))
            ->assertStatus(200)
            ->assertSee($bill->contact_name)
            ->assertSee($bill->contact_email);
    }

    public function testItShouldUpdateBill()
    {
        $request = $this->getBillRequest();

        $bill = $this->dispatch(new CreateBill($request));

        $request['contact_name'] = $this->faker->name;

        $this->loginAs()
            ->patch(route('bills.update', $bill->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteBill()
    {
        $bill = $this->dispatch(new CreateBill($this->getBillRequest()));

        $this->loginAs()
            ->delete(route('bills.destroy', $bill->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    private function getBillRequest($recurring = false)
    {
        $amount = $this->faker->randomFloat(2, 2);

        $items = [['name' => $this->faker->text(5), 'item_id' => null, 'quantity' => '1', 'price' => $amount, 'currency' => 'USD', 'tax_id' => null]];

        $data =  [
            'company_id' => $this->company->id,
            'billed_at' => $this->faker->date(),
            'due_at' => $this->faker->date(),
            'bill_number' => '1',
            'order_number' => '1',
            'currency_code' => setting('default.currency', 'USD'),
            'currency_rate' => '1',
            'items' => $items,
            'discount' => '0',
            'notes' => $this->faker->text(5),
            'category_id' => $this->company->categories()->type('expense')->pluck('id')->first(),
            'recurring_frequency' => 'no',
            'contact_id' => '0',
            'contact_name' =>  $this->faker->name,
            'contact_email' =>$this->faker->email,
            'contact_tax_number' => null,
            'contact_phone' =>  null,
            'contact_address' =>  $this->faker->address,
            'bill_status_code' => 'draft',
            'amount' => $amount,
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
