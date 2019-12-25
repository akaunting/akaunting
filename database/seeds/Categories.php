<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Models\Setting\Category;
use Illuminate\Database\Seeder;

class Categories extends Seeder
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
                'name' => trans_choice('general.transfers', 1),
                'type' => 'other',
                'color' => '#3c3f72',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('demo.categories.deposit'),
                'type' => 'income',
                'color' => '#efad32',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('demo.categories.sales'),
                'type' => 'income',
                'color' => '#6da252',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => trans_choice('general.others', 1),
                'type' => 'expense',
                'color' => '#e5e5e5',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('general.general'),
                'type' => 'item',
                'color' => '#328aef',
                'enabled' => '1',
            ],
        ];

        foreach ($rows as $row) {
            Category::create($row);
        }
    }
}
