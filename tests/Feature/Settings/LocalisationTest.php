<?php

namespace Tests\Feature\Settings;

use Tests\Feature\FeatureTestCase;

class LocalisationTest extends FeatureTestCase
{
    public function testItShouldSeeLocalisationUpdatePage()
    {
        $this->loginAs()
            ->get(route('settings.localisation.edit'))
            ->assertStatus(200);
    }

    public function testItShouldUpdateLocalisation()
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
            'financial_start'=>'',
            'timezone'=>$this->faker->timezone,
            'date_format'=> 'd M Y',
            'date_separator'=>' ',
            'percent_position'=>'after',
            'discount_location'=>'At total'
        ];

    }
}
