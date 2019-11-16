<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Models\Common\Widget;
use Illuminate\Database\Seeder;

class Widgets extends Seeder
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
                'name' => trans('dashboard.total_incomes'),
                'alias' => 'total-incomes',
                'settings' => ['width'=>'col-md-4'],
                'enabled' => 1,
            ],
            [
                'company_id' => $company_id,
                'name' => trans('dashboard.total_expenses'),
                'alias' => 'total-expenses',
                'settings' => ['width'=>'col-md-4'],
                'enabled' => 1,
            ],
            [
                'company_id' => $company_id,
                'name' => trans('dashboard.total_profit'),
                'alias' => 'total-profit',
                'settings' => ['width'=>'col-md-4'],
                'enabled' => 1,
            ],
            [
                'company_id' => $company_id,
                'name' => trans('dashboard.cash_flow'),
                'alias' => 'cash-flow',
                'settings' => ['width'=>'col-md-12'],
                'enabled' => 1,
            ],
            [
                'company_id' => $company_id,
                'name' => trans('dashboard.incomes_by_category'),
                'alias' => 'incomes-by-category',
                'settings' => ['width'=>'col-md-6'],
                'enabled' => 1,
            ],
            [
                'company_id' => $company_id,
                'name' => trans('dashboard.expenses_by_category'),
                'alias' => 'expenses-by-category',
                'settings' => ['width'=>'col-md-6'],
                'enabled' => 1,
            ],
            [
                'company_id' => $company_id,
                'name' => trans('dashboard.account_balance'),
                'alias' => 'account-balance',
                'settings' => ['width'=>'col-md-4'],
                'enabled' => 1,
            ],
            [
                'company_id' => $company_id,
                'name' => trans('dashboard.latest_incomes'),
                'alias' => 'latest-incomes',
                'settings' => ['width'=>'col-md-4'],
                'enabled' => 1,
            ],
            [
                'company_id' => $company_id,
                'name' => trans('dashboard.latest_expenses'),
                'alias' => 'latest-expenses',
                'settings' => ['width'=>'col-md-4'],
                'enabled' => 1,
            ]
        ];

        foreach ($rows as $row) {
            Widget::create($row);
        }
    }
}
