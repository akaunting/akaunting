<?php

namespace Tests\Feature\Settings;

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
            ->post(route('taxes.store'), $this->getTaxRequest())
            ->assertStatus(302)
            ->assertRedirect(route('taxes.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldUpdateTax()
    {
        $request = $this->getTaxRequest();

        $tax = Tax::create($request);

        $request['name'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(route('taxes.update', $tax->id), $request)
            ->assertStatus(302)
            ->assertRedirect(route('taxes.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteTax()
    {
        $tax = Tax::create($this->getTaxRequest());

        $this->loginAs()
            ->delete(route('taxes.destroy', $tax->id))
            ->assertStatus(302)
            ->assertRedirect(route('taxes.index'));

        $this->assertFlashLevel('success');
    }

    private function getTaxRequest()
    {
        return [
            'company_id' => $this->company->id,
            'name' => $this->faker->text(15),
            'rate' => $this->faker->randomFloat(2, 10, 20),
            'type' => $this->faker->randomElement(['normal', 'inclusive', 'compound']),
            'enabled' => $this->faker->boolean ? 1 : 0
        ];
    }
}
