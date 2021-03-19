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
        $request = $this->getRequest();

        $this->loginAs()
            ->post(route('wizard.currencies.store'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertDatabaseHas('currencies', [
            'code' => $request['code'],
        ]);
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

        $this->assertDatabaseHas('currencies', [
            'code' => $request['code'],
        ]);
    }

    public function testItShouldDeleteCurrency()
    {
        $request = $this->getRequest();

        $currency = $this->dispatch(new CreateCurrency($request));

        $this->loginAs()
            ->delete(route('wizard.currencies.destroy', $currency->id))
            ->assertStatus(200);

        $this->assertFlashLevel('success');

        $this->assertSoftDeleted('currencies', [
            'code' => $request['code'],
        ]);
    }

    public function getRequest()
    {
        return Currency::factory()->enabled()->raw();
    }
}
