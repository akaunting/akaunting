<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Jobs\Common\CreateDashboard;
use App\Traits\Jobs;
use Illuminate\Database\Seeder;

class Dashboards extends Seeder
{
    use Jobs;

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
        $user_id = $this->command->argument('user');
        $company_id = $this->command->argument('company');

        $this->dispatch(new CreateDashboard([
            'company_id' => $company_id,
            'name' => trans_choice('general.dashboards', 1),
            'custom_widgets' => [
                'App\Widgets\Receivables',
                'App\Widgets\Payables',
                'App\Widgets\CashFlow',
                'App\Widgets\ProfitLoss',
                'App\Widgets\ExpensesByCategory',
                'App\Widgets\AccountBalance',
                'App\Widgets\BankFeeds',
            ],
            'users' => $user_id,
            'created_from' => 'core::seed',
        ]));
    }
}
