<?php

namespace Database\Seeds;

use App\Abstracts\Model;
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
                'name' => 'Standard-rated supplies',
                'rate' => '7',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => 'Out-of-scope supplies',
                'rate' => '0',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => 'Zero-rated supplies',
                'rate' => '0',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => 'Purchases with GST 7%',
                'rate' => '7',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => 'Purchases exempted from GST',
                'rate' => '0',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => 'Out-of-scope purchases',
                'rate' => '0',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => 'Import goods with GST 7%',
                'rate' => '7',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => 'Imports under special scheme',
                'rate' => '0',
                'enabled' => '1',
            ]
        ];

        foreach ($rows as $row) {
            Tax::create($row);
        }
    }
}
