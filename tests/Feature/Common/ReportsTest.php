<?php

namespace Tests\Feature\Common;

use App\Reports\ProfitLoss;
use App\Models\Common\Report;
use Tests\Feature\FeatureTestCase;

class ReportsTest extends FeatureTestCase
{
    public function testItShouldShowProfitLossReportWhenSettingsAreMissing()
    {
        $report = Report::create([
            'company_id' => company_id(),
            'class' => 'App\Reports\ProfitLoss',
            'name' => 'Legacy Profit Loss',
            'description' => 'Report without saved settings',
            'settings' => null,
            'created_from' => 'core::test',
            'created_by' => $this->user->id,
        ]);

        $this->loginAs()
            ->get(route('reports.show', $report->id))
            ->assertOk()
            ->assertSeeText('Legacy Profit Loss')
            ->assertSeeText(trans('reports.net_profit'));
    }

    public function testItShouldRenderProfitLossFooterWhenIncomeTotalsAreMissing()
    {
        $report = new Report([
            'class' => ProfitLoss::class,
            'name' => 'Profit Loss Footer',
            'description' => 'Footer regression coverage',
            'settings' => ['group' => 'category', 'period' => 'quarterly', 'basis' => 'accrual'],
        ]);

        $class = new ProfitLoss($report, false);
        $class->dates = ['Q1 2026', 'Q2 2026'];
        $class->footer_totals = [
            'expense' => ['Q1 2026' => 125],
        ];

        $html = view('reports.profit_loss.table.footer', [
            'class' => $class,
            'table_key' => 'income',
        ])->render();

        $this->assertStringContainsString(trans_choice('general.totals', 1), $html);
        $this->assertStringNotContainsString('Undefined array key', $html);
    }

    public function testItShouldRenderMoneyComponentWhenAmountIsEmptyString()
    {
        $html = view('money::components.money', [
            'amount' => '',
            'currency' => 'USD',
            'convert' => false,
        ])->render();

        $this->assertNotEmpty($html);
        $this->assertStringNotContainsString('Invalid amount', $html);
    }
}
