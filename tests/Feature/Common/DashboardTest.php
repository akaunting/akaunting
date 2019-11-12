<?php

namespace Tests\Feature\Common;

use Tests\Feature\FeatureTestCase;

class DashboardTest extends FeatureTestCase
{
    public function testItShouldSeeDashboard()
    {
        $this->loginAs()
            ->get(url('/'))
            ->assertStatus(200)
            ->assertSeeText(trans('general.dashboard'));
    }
}
