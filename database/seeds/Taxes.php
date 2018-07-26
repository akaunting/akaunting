<?php

namespace Database\Seeds;

use App\Models\Model;
use App\Models\Setting\Tax;

use Illuminate\Database\Seeder;

class Taxes extends Seeder
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
                'name' => trans('demo.taxes_exempt'),
                'rate' => '0',
                'enabled' => '1'
            ],
            [
                'company_id' => $company_id,
                'name' => trans('demo.taxes_normal'),
                'rate' => '5',
                'enabled' => '1'
            ],
            [
                'company_id' => $company_id,
                'name' => trans('demo.taxes_sales'),
                'rate' => '15',
                'enabled' => '1'
            ],
        ];

        foreach ($rows as $row) {
            Tax::create($row);
        }
    }
}
