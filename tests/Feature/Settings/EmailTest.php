<?php

namespace Tests\Feature\Settings;

use Tests\Feature\FeatureTestCase;

class EmailTest extends FeatureTestCase
{
    public function testItShouldSeEmailUpdatePage()
    {
        $this->loginAs()
            ->get(route('settings.email.edit'))
            ->assertStatus(200);
    }

    public function testItShouldUpdateEmail()
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
            'protocol' => 'smtp'
        ];
    }
}