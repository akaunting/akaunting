<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Models\Common\Report;
use Illuminate\Database\Seeder;

class Reports extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->create();

        Model::reguard();
    }

    private function create()
    {
        $company_id = $this->command->argument('company');

        $rows = [
            [
                'company_id' => $company_id,
                'class' => 'App\Reports\IncomeSummary',
                'name' => trans('reports.summary.income'),
                'description' => trans('demo.reports.income'),
                'settings' => ['group' => 'category', 'period' => 'monthly', 'basis' => 'accrual', 'chart' => 'line'],
            ],
            [
                'company_id' => $company_id,
                'class' => 'App\Reports\ExpenseSummary',
                'name' => trans('reports.summary.expense'),
                'description' => trans('demo.reports.expense'),
                'settings' => ['group' => 'category', 'period' => 'monthly', 'basis' => 'accrual', 'chart' => 'line'],
            ],
            [
                'company_id' => $company_id,
                'class' => 'App\Reports\IncomeExpenseSummary',
                'name' => trans('reports.summary.income_expense'),
                'description' => trans('demo.reports.income_expense'),
                'settings' => ['group' => 'category', 'period' => 'monthly', 'basis' => 'accrual', 'chart' => 'line'],
            ],
            [
                'company_id' => $company_id,
                'class' => 'App\Reports\ProfitLoss',
                'name' => trans('reports.profit_loss'),
                'description' => trans('demo.reports.profit_loss'),
                'settings' => ['group' => 'category', 'period' => 'quarterly', 'basis' => 'accrual'],
            ],
            [
                'company_id' => $company_id,
                'class' => 'App\Reports\TaxSummary',
                'name' => trans('reports.summary.tax'),
                'description' => trans('demo.reports.tax'),
                'settings' => ['period' => 'quarterly', 'basis' => 'accrual'],
            ],
        ];

        foreach ($rows as $row) {
            Report::create($row);
        }
    }
}
