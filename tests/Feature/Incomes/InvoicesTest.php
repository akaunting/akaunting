<?php

namespace Tests\Feature\Incomes;

use App\Jobs\Income\CreateInvoice;
use Tests\Feature\FeatureTestCase;

class InvoicesTest extends FeatureTestCase
{
    public function testItShouldSeeInvoiceListPage()
    {
        $this->loginAs()
            ->get(route('invoices.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.invoices', 2));
    }

    public function testItShouldSeeInvoiceCreatePage()
    {
        $this->loginAs()
            ->get(route('invoices.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.invoices', 1)]));
    }

    public function testItShouldCreateInvoice()
    {
        $this->loginAs()
            ->post(route('invoices.store'), $this->getInvoiceRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldCreateInvoiceWithRecurring()
    {
        $this->loginAs()
            ->post(route('invoices.store'), $this->getInvoiceRequest(true))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeInvoiceUpdatePage()
    {
        $invoice = $this->dispatch(new CreateInvoice($this->getInvoiceRequest()));

        $this->loginAs()
            ->get(route('invoices.edit', ['invoice' => $invoice->id]))
            ->assertStatus(200)
            ->assertSee($invoice->contact_name)
            ->assertSee($invoice->contact_email);
    }

    public function testItShouldUpdateInvoice()
    {
        $request = $this->getInvoiceRequest();

        $invoice = $this->dispatch(new CreateInvoice($request));

        $request['contact_name'] = $this->faker->name;

        $this->loginAs()
            ->patch(route('invoices.update', $invoice->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteInvoice()
    {
        $invoice = $this->dispatch(new CreateInvoice($this->getInvoiceRequest()));

        $this->loginAs()
            ->delete(route('invoices.destroy', $invoice->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    private function getInvoiceRequest($recurring = false)
    {
        $amount = $this->faker->randomFloat(2, 2);

        $items = [['name' => $this->faker->text(5), 'item_id' => null, 'quantity' => '1', 'price' => $amount, 'currency' => 'USD']];

        $data = [
            'company_id' => $this->company->id,
            'invoiced_at' => $this->faker->date(),
            'due_at' => $this->faker->date(),
            'invoice_number' => '1',
            'order_number' =>  '1',
            'currency_code' => setting('default.currency', 'USD'),
            'currency_rate' => '1',
            'items' => $items,
            'discount' => '0',
            'notes' => $this->faker->text(5),
            'category_id' => $this->company->categories()->type('income')->pluck('id')->first(),
            'recurring_frequency' => 'no',
            'contact_id' => '0',
            'contact_name' =>  $this->faker->name,
            'contact_email' =>$this->faker->email,
            'contact_tax_number' => null,
            'contact_phone' =>  null,
            'contact_address' =>  $this->faker->address,
            'invoice_status_code' => 'draft',
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
