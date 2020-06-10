<?php

namespace Tests\Feature\Settings;

use Tests\Feature\FeatureTestCase;

class InvoiceTest extends FeatureTestCase
{
    public function testItShouldSeeInvoiceUpdatePage()
    {
        $this->loginAs()
            ->get(route('settings.invoice.edit'))
            ->assertStatus(200);
    }

    public function testItShouldUpdateInvoice()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->patch(route('settings.update'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return [
            'number_prefix' => 'INV-',
            'number_digit' => '5',
            'number_next' => '1',
            'payment_terms' => '0',
            'subheading' => $this->faker->name,
            'notes' => $this->faker->text,
            'footer' => $this->faker->text,
            'item_name' => 'settings.invoice.item',
            'price_name' => 'settings.invoice.price',
            'quantity_name' => 'settings.invoice.quantity',
            'invoice_template' => 'default',
        ];
    }

}
