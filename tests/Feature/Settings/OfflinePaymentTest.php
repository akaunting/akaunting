<?php

namespace Tests\Feature\Settings;

use Tests\Feature\FeatureTestCase;

class OfflinePaymentTest extends FeatureTestCase
{
    public function testItShouldSeeOfflinePaymetEditPage()
    {
        $this->loginAs()
            ->get(route('offline-payments.settings.edit'))
            ->assertStatus(200);
    }


    public function testItShouldUpdateOfflinePaymentSetting()
    {
        $request = $this->getRequest();

        $this->loginAs()
            ->patch(route('offline-payments.settings.update'), $request)
            ->assertStatus(200);

        $this->assertFlashLevel('success');
    }

    public function getRequest()
    {
        return [
            'name' => $this->faker->name,
            'code' => 'offline-payments.cash.1',
            'customer' => 1,
            'description' => $this->faker->paragraph,
            'order' => 1234,
        ];
    }

}



