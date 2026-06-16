<?php

namespace Database\Seeds;

use App\Abstracts\Model;
use App\Jobs\Setting\CreateCategory;
use App\Models\Setting\Category;
use App\Traits\Jobs;
use Illuminate\Database\Seeder;

class Categories extends Seeder
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
        $company_id = $this->command->argument('company');

        $rows = [
            [
                'company_id' => $company_id,
                'name' => trans_choice('general.transfers', 1),
                'type' => Category::OTHER_TYPE,
                'color' => '#3c3f72',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('demo.categories.deposit'),
                'type' => Category::INCOME_TYPE,
                'color' => '#efad32',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('demo.categories.sales'),
                'type' => Category::INCOME_TYPE,
                'color' => '#6da252',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => trans_choice('general.others', 1),
                'type' => Category::OTHER_TYPE,
                'color' => '#e5e5e5',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => trans('general.general'),
                'type' => Category::ITEM_TYPE,
                'color' => '#328aef',
                'enabled' => '1',
            ],
            [
                'company_id' => $company_id,
                'name' => trans_choice('general.cogs', 1),
                'type' => Category::DIRECT_COST_TYPE,
                'color' => '#ef3281',
                'enabled' => '1',
            ],
        ];

        $income_category_id = $expense_category_id = 0;

        foreach ($rows as $row) {
            $row['created_from'] = 'core::seed';

            $category = $this->dispatch(new CreateCategory($row));

            switch ($category->type) {
                case Category::INCOME_TYPE:
                    if (empty($income_category_id)) {
                        $income_category_id = $category->id;
                    }

                    break;
                case Category::EXPENSE_TYPE:
                    if (empty($expense_category_id)) {
                        $expense_category_id = $category->id;
                    }

                    break;
            }
        }

        setting()->set('default.income_category', $income_category_id);
        setting()->set('default.expense_category', $expense_category_id);
    }
}
