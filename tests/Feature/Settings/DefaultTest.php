<?php

namespace Tests\Feature\Settings;

use Tests\Feature\FeatureTestCase;

class DefaultTest extends FeatureTestCase
{
    public function testItShouldSeeDefaultUpdatePage()
    {
        $this->loginAs()
            ->get(route('settings.default.edit'))
            ->assertStatus(200);
    }

    public function testItShouldUpdateDefault()
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
            'account' => 1,
            'currency' => 'GBP',
            'tax' => $this->faker->randomNumber(8),
            'payment_method' => 'offline-payments.bank_transfer.2',
            'list_limit'=>50,
            'user_gravatar' => 0
        ];
    }
}