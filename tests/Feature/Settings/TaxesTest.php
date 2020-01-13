<?php

namespace Tests\Feature\Settings;

use App\Jobs\Setting\CreateTax;
use App\Models\Setting\Tax;
use Tests\Feature\FeatureTestCase;

class TaxesTest extends FeatureTestCase
{
    public function testItShouldSeeTaxListPage()
    {
        $this->loginAs()
            ->get(route('taxes.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.tax_rates', 2));
    }

    public function testItShouldSeeTaxCreatePage()
    {
        $this->loginAs()
            ->get(route('taxes.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.taxes', 1)]));
    }

    public function testItShouldCreateTax()
    {
        $this->loginAs()
            ->post(route('taxes.store'), $this->getRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldSeeTaxUpdatePage()
    {
        $tax = $this->dispatch(new CreateTax($this->getRequest()));

        $this->loginAs()
            ->get(route('taxes.edit', $tax->id))
            ->assertStatus(200)
            ->assertSee($tax->name);
    }

    public function testItShouldUpdateTax()
    {
        $request = $this->getRequest();

        $tax = $this->dispatch(new CreateTax($request));

        $request['name'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(route('taxes.update', $tax->id), $request)
            ->assertStatus(200)
			->assertSee($request['name']);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteTax()
    {
        $tax = $this->dispatch(new CreateTax($this->getRequest()));

        $this->loginAs()
            ->delete(route('taxes.destroy', $tax->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return factory(Tax::class)->states('enabled')->raw();
    }
}
