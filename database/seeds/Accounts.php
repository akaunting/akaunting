<?php

namespace Database\Seeds;

use App\Models\Model;
use App\Models\Banking\Account;
use Illuminate\Database\Seeder;

class Accounts extends Seeder
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

        setting()->forgetAll();
        setting()->setExtraColumns(['company_id' => $company_id]);

        $rows = [
            [
                'company_id' => $company_id,
                'name' => trans('demo.accounts_cash'),
                'number' => '1',
                'currency_code' => 'USD',
                'bank_name' => trans('demo.accounts_cash'),
                'enabled' => '1',
            ],
        ];

        foreach ($rows as $row) {
            $account = Account::create($row);

            setting()->set('general.default_account', $account->id);
        }
    }
}
