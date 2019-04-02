<?php

namespace Tests\Feature\Settings;

use App\Models\Setting\Currency;
use Tests\Feature\FeatureTestCase;

class CurrenciesTest extends FeatureTestCase
{
    public function testItShouldSeeCurrencyListPage()
    {
        $this->loginAs()
            ->get(route('currencies.index'))
            ->assertStatus(200)
            ->assertSeeText(trans_choice('general.currencies', 2));
    }

    public function testItShouldSeeCurrencyCreatePage()
    {
        $this->loginAs()
            ->get(route('currencies.create'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.title.new', ['type' => trans_choice('general.currencies', 1)]));
    }

    public function testItShouldCreateCurrency()
    {
        $this->loginAs()
            ->post(route('currencies.store'), $this->getCurrencyRequest())
            ->assertStatus(302)
            ->assertRedirect(route('currencies.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldUpdateCurrency()
    {
        $request = $this->getCurrencyRequest();

        $currency = Currency::create($request);

        $request['name'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(route('currencies.update', $currency->id), $request)
            ->assertStatus(302)
            ->assertRedirect(route('currencies.index'));

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteCurrency()
    {
        $currency = Currency::create($this->getCurrencyRequest());

        $this->loginAs()
            ->delete(route('currencies.destroy', $currency->id))
            ->assertStatus(302)
            ->assertRedirect(route('currencies.index'));

        $this->assertFlashLevel('success');
    }

    private function getCurrencyRequest()
    {
        return [
            'company_id' => $this->company->id,
            'name' => $this->faker->text(15),
            'code' => $this->faker->text(strtoupper(5)),
            'rate' => $this->faker->boolean(1),
            'precision' => $this->faker->text(5),
            'symbol' => $this->faker->text(5),
            'symbol_first' => 1,
            'symbol_position' => 'after_amount',
            'decimal_mark' => $this->faker->text(5),
            'thousands_separator' => $this->faker->text(5),
            'enabled' => $this->faker->boolean ? 1 : 0,
            'default_currency' => $this->faker->boolean ? 1 : 0
        ];
    }
}
