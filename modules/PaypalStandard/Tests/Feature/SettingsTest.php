<?php

namespace Modules\PaypalStandard\Tests\Feature;

use Tests\Feature\FeatureTestCase;

class SettingsTest extends FeatureTestCase
{
    public function testItShouldSeePaypalStandardInSettingsListPage()
    {
        $this->loginAs()
            ->get(route('settings.index'))
            ->assertStatus(200)
            ->assertSeeText(trans('paypal-standard::general.description'));
    }

    public function testItShouldSeePaypalStandardSettingsUpdatePage()
    {
        $this->loginAs()
            ->get(route('settings.module.edit', ['alias' => 'paypal-standard']))
            ->assertStatus(200);
    }

    public function testItShouldUpdatePaypalStandardSettings()
    {
        $this->loginAs()
            ->patch(route('settings.module.edit', ['alias' => 'paypal-standard']), $this->getRequest())
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'mode' => 'sandbox',
            'transaction' => 'sale',
            'customer' => 1,
            'debug' => 1,
            'order' => 1,
        ];
    }
}
