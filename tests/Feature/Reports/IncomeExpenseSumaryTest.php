<?php

namespace Tests\Feature\Reports;

use Tests\Feature\FeatureTestCase;

class IncomeExpenseSummaryTest extends FeatureTestCase
{
    public function testItShouldSeeIncomeExpenseSummaryPage()
    {
        $this->loginAs()
            ->get(url('reports/income-expense-summary'))
            ->assertStatus(200)
            ->assertSeeText(trans('reports.summary.income_expense'));
    }
}
