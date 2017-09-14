<?php

namespace Database\Seeds;

use App\Models\Model;
use App\Models\Setting\Currency;

use Illuminate\Database\Seeder;

class Currencies extends Seeder
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
                'name' => trans('demo.currencies_usd'),
                'code' => 'USD',
                'rate' => '1.00',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('demo.currencies_eur'),
                'code' => 'EUR',
                'rate' => '1.25',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('demo.currencies_gbp'),
                'code' => 'GBP',
                'rate' => '1.60',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('demo.currencies_try'),
                'code' => 'TRY',
                'rate' => '0.80',
            ],
        ];

        foreach ($rows as $row) {
            Currency::create($row);
        }
    }
}
