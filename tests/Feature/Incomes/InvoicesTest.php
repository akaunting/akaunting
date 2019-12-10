<?php

namespace Tests\Feature\Incomes;

use App\Models\Common\Item;
use App\Models\Income\Customer;
use App\Models\Income\Invoice;
use Tests\Feature\FeatureTestCase;

class InvoicesTest extends FeatureTestCase
{
    public function testItShouldSeeInvoiceListPage()
    {
        $this->loginAs()
            ->get(url('incomes/invoices'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.invoices', 2));
    }

    public function testItShouldSeeInvoiceCreatePage()
    {
        $this->loginAs()
            ->get(url('incomes/invoices'))
            ->assertStatus(200)
            ->assertSeeText(trans( trans_choice('general.invoices', 1)));
    }

    public function testItShouldCreateInvoice()
    {
        $this->loginAs()
            ->post(url('incomes/invoices'), $this->getInvoiceRequest())
            ->assertStatus(302)
            ->assertRedirect(url('incomes/invoices', ['invoice' => 1]));

        $this->assertFlashLevel('success');
    }

    public function testItShouldCreateInvoiceWithRecurring()
    {
        $this->loginAs()
            ->post(url('incomes/invoices'), $this->getInvoiceRequest(1))
            ->assertStatus(302)
            ->assertRedirect(url('incomes/invoices', ['invoice' => 1]));

        $this->assertFlashLevel('success');
    }

    public function testItShouldUpdateInvoice()
    {
        $request = $this->getInvoiceRequest();

        $invoice = Invoice::create($request);

        $request['customer_name'] = $this->faker->name;

        $this->loginAs()
            ->patch(url('incomes/invoices', $invoice->id), $request)
            ->assertStatus(302);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteInvoice()
    {
        $invoice = Invoice::create($this->getInvoiceRequest());

        $this->loginAs()
            ->delete(url('incomes/invoices', $invoice->id))
            ->assertStatus(302)
            ->assertRedirect(url('incomes/invoices'));

        $this->assertFlashLevel('success');
    }

    private function getInvoiceRequest($recurring = 0)
    {
        $amount = $this->faker->randomFloat(2, 2);

        $items = [['name' =>  $this->faker->text(5), 'item_id' => null, 'quantity' => '1', 'price' => $amount, 'currency' => 'USD']];

        $data = [
            'customer_id' => '0',
            'invoiced_at' => $this->faker->date(),
            'due_at' => $this->faker->date(),
            'invoice_number' => '1',
            'order_number' =>  '1',
            'currency_code' => setting('general.default_currency'),
            'currency_rate' => '1',
            'item' => $items,
            'discount' => '0',
            'notes' => $this->faker->text(5),
            'category_id' => $this->company->categories()->type('income')->first()->id,
            'recurring_frequency' => 'no',
            'customer_name' =>  $this->faker->name,
            'customer_email' =>$this->faker->email,
            'customer_tax_number' => null,
            'customer_phone' =>  null,
            'customer_address' =>  $this->faker->address,
            'invoice_status_code' => 'draft',
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
