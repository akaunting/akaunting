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
                'name' => trans('reports.summary.income'),
                'description' => 'This is the income summary by category.',
                'class' => 'App\Reports\IncomeSummary',
                'group' => 'category',
                'period' => 'monthly',
                'basis' => 'accrual',
                'chart' => 'line',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('reports.summary.expense'),
                'description' => 'This is the expense summary by category.',
                'class' => 'App\Reports\ExpenseSummary',
                'group' => 'category',
                'period' => 'monthly',
                'basis' => 'accrual',
                'chart' => 'line',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('reports.summary.income_expense'),
                'description' => 'This is the income vs expense by category.',
                'class' => 'App\Reports\IncomeExpenseSummary',
                'group' => 'category',
                'period' => 'monthly',
                'basis' => 'accrual',
                'chart' => 'line',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('reports.summary.tax'),
                'description' => 'This is the tax summary by category.',
                'class' => 'App\Reports\TaxSummary',
                'group' => 'category',
                'period' => 'quarterly',
                'basis' => 'accrual',
                'chart' => '0',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('reports.profit_loss'),
                'description' => 'This is the profit & loss by category.',
                'class' => 'App\Reports\ProfitLoss',
                'group' => 'category',
                'period' => 'quarterly',
                'basis' => 'accrual',
                'chart' => '0',
            ],
        ];

        foreach ($rows as $row) {
            Report::create($row);
        }
    }
}
