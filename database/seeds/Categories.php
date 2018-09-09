<?php

namespace Database\Seeds;

use App\Models\Model;
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
                'color' => '#605ca8',
                'enabled' => '1'
            ],
            [
                'company_id' => $company_id,
                'name' => trans('demo.categories_deposit'),
                'type' => 'income',
                'color' => '#f39c12',
                'enabled' => '1'
            ],
            [
                'company_id' => $company_id,
                'name' => trans('demo.categories_sales'),
                'type' => 'income',
                'color' => '#6da252',
                'enabled' => '1'
            ],
            [
                'company_id' => $company_id,
                'name' => trans_choice('general.others', 1),
                'type' => 'expense',
                'color' => '#d2d6de',
                'enabled' => '1'
            ],
            [
                'company_id' => $company_id,
                'name' => trans('general.general'),
                'type' => 'item',
                'color' => '#00c0ef',
                'enabled' => '1'
            ],
        ];

        foreach ($rows as $row) {
            Category::create($row);
        }
    }
}
