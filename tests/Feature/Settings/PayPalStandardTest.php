<?php

namespace Tests\Feature\Settings;

use Tests\Feature\FeatureTestCase;

class PayPalStandardTest extends FeatureTestCase
{
    public function testItShouldSeePayPalStandardEditPage()
    {
        $this->loginAs()
            ->get(route('settings.module.edit', ['alias' => 'paypal-standard']))
            ->assertStatus(200);
    }


    public function testItShouldUpdatePayPalStandardSetting()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->patch(route('settings.module.edit', ['alias' => 'paypal-standard']), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'customer' => 1,
            'debug' => '628',
            'order' => 1234,
            'mode' => 'live',
            'transaction' => 'authorization'
        ];
    }

}



