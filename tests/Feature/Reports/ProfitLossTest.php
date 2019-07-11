<?php

namespace Tests\Feature\Reports;

use Tests\Feature\FeatureTestCase;

class ProfitLossTest extends FeatureTestCase
{
    public function testItShouldSeeProfitLossPage()
    {
        $this->loginAs()
            ->get(url('reports/profit-loss'))
            ->assertStatus(200)
            ->assertSeeText(trans('reports.profit_loss'));
    }
}
