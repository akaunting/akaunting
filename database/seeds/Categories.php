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

        $sales_category = $purchases_category = false;

        foreach ($rows as $row) {
            $category = Category::create($row);

            switch ($category->type) {
                case 'income':
                    if (empty($sales_category)) {
                        $sales_category = $category;
                    }
                    break;
                case 'expense':
                    if (empty($purchases_category)) {
                        $purchases_category = $category;
                    }
                    break;
            }
        }

        setting()->set('default.sales_category', $sales_category->id);
        setting()->set('default.purchases_category', $purchases_category->id);
    }
}
