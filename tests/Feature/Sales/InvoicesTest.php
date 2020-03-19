<?php

namespace Tests\Feature\Sales;

use App\Jobs\Sale\CreateInvoice;
use App\Models\Sale\Invoice;
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
            ->post(route('invoices.store'), $this->getRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldCreateInvoiceWithRecurring()
    {
        $this->loginAs()
            ->post(route('invoices.store'), $this->getRequest(true))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeInvoiceUpdatePage()
    {
        $invoice = $this->dispatch(new CreateInvoice($this->getRequest()));

        $this->loginAs()
            ->get(route('invoices.edit', $invoice->id))
            ->assertStatus(200)
            ->assertSee($invoice->contact_email);
    }

    public function testItShouldUpdateInvoice()
    {
        $request = $this->getRequest();

        $invoice = $this->dispatch(new CreateInvoice($request));

        $request['contact_email'] = $this->faker->safeEmail;

        $this->loginAs()
            ->patch(route('invoices.update', $invoice->id), $request)
            ->assertStatus(200)
			->assertSee($request['contact_email']);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteInvoice()
    {
        $invoice = $this->dispatch(new CreateInvoice($this->getRequest()));

        $this->loginAs()
            ->delete(route('invoices.destroy', $invoice->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function getRequest($recurring = false)
    {
        $factory = factory(Invoice::class);

        $recurring ? $factory->states('items', 'recurring') : $factory->states('items');

        return $factory->raw();
    }
}
