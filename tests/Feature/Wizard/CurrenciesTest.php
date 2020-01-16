<?php
namespace Tests\Feature\Wizard;

use App\Jobs\Setting\CreateCurrency;
use App\Models\Setting\Currency;
use Tests\Feature\FeatureTestCase;

class CurrenciesTest extends FeatureTestCase
{
    public function testItShouldSeeCurrencyListPage()
    {
        $this->loginAs()
            ->get(route('wizard.currencies.index'))
            ->assertStatus(200)
            ->assertSeeText(trans('demo.currencies.usd'));
    }

    public function testItShouldCreateCurrency()
    {
        $this->loginAs()
            ->post(route('wizard.currencies.store'), $this->getRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldUpdateCurrency()
    {
        $request = $this->getRequest();

        $currency = $this->dispatch(new CreateCurrency($request));

        $request['name'] = $this->faker->text(15);

        $this->loginAs()
            ->patch(route('wizard.currencies.update', $currency->id), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function testItShouldDeleteCurrency()
    {
        $currency = $this->dispatch(new CreateCurrency($this->getRequest()));

        $this->loginAs()
            ->delete(route('wizard.currencies.destroy', $currency->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return factory(Currency::class)->states('enabled')->raw();
    }
}
