<?php

namespace Tests\Feature\Settings;

use Tests\Feature\FeatureTestCase;

class ScheduleTest extends FeatureTestCase
{
    public function testItShouldSeeScheduleUpdatePage()
    {
        $this->loginAs()
            ->get(route('settings.schedule.edit'))
            ->assertStatus(200);
    }

    public function testItShouldUpdateSchedule()
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
            'send_invoice_reminder' => $this->faker->randomNumber(1),
            'invoice_days' => '1,3,5,10',
            'send_bill_reminder' => $this->faker->randomNumber(1),
            'bill_days' => '10,5,3,1',
            'time' => '09:00'
        ];
    }
}