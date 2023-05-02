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
            ->assertSeeText(trans('general.wizard'));
    }

    public function testItShouldCreateCurrency()
    {
        $request = $this->getRequest();

        $message = trans('messages.success.added', ['type' => trans_choice('general.currencies', 1)]);

        $this->loginAs()
            ->post(route('wizard.currencies.store'), $request)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => $message,
            ]);

        $this->assertDatabaseHas('currencies', [
            'code' => $request['code'],
        ]);
    }

    public function testItShouldUpdateCurrency()
    {
        $request = $this->getRequest();

        $currency = $this->dispatch(new CreateCurrency($request));

        $request['name'] = $this->faker->text(15);

        $message = trans('messages.success.updated', ['type' => $request['name']]);

        $this->loginAs()
            ->patch(route('wizard.currencies.update', $currency->id), $request)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => $message,
            ]);

        $this->assertDatabaseHas('currencies', [
            'code' => $request['code'],
        ]);
    }

    public function testItShouldDeleteCurrency()
    {
        $request = $this->getRequest();

        $currency = $this->dispatch(new CreateCurrency($request));

        $message = trans('messages.success.deleted', ['type' => $currency->name]);

        $this->loginAs()
            ->delete(route('wizard.currencies.destroy', $currency->id))
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => $message,
            ]);

        $this->assertSoftDeleted('currencies', [
            'code' => $request['code'],
        ]);
    }

    public function getRequest()
    {
        return Currency::factory()->enabled()->raw();
    }
}
