<?php

namespace Tests\Feature\Reports;

use Tests\Feature\FeatureTestCase;

class ExpenseSummaryTest extends FeatureTestCase
{
    public function testItShouldSeeExpenseSummaryPage()
    {
        $this->loginAs()
            ->get(url('reports/expense-summary'))
            ->assertStatus(200)
            ->assertSeeText(trans('reports.summary.expense'));
    }
}
