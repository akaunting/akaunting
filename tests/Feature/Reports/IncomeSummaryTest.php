<?php

namespace Tests\Feature\Reports;

use Tests\Feature\FeatureTestCase;

class IncomeSummaryTest extends FeatureTestCase
{
    public function testItShouldSeeIncomeSummaryPage()
    {
        $this->loginAs()
            ->get(url('reports/income-summary'))
            ->assertStatus(200)
            ->assertSeeText(trans('reports.summary.income'));
    }
}
