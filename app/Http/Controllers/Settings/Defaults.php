<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\SettingController;
use App\Models\Banking\Account;
use App\Models\Setting\Category;
use App\Models\Setting\Tax;

class Defaults extends SettingController
{
    public function edit()
    {
        $accounts = Account::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $sales_categories = Category::income()->enabled()->orderBy('name')->take(setting('default.select_limit'))->get();

        $sale_category_id = setting('default.income_category');

        if ($sale_category_id && !$sales_categories->pluck('id')->flip()->has($sale_category_id)) {
            $category = Category::find($sale_category_id);

            if ($category) {
                $sales_categories->put($category->id, $category->name);
            }
        }

        $purchases_categories = Category::expense()->enabled()->orderBy('name')->take(setting('default.select_limit'))->get();

        $expense_category_id = setting('default.expense_category');

        if ($expense_category_id && !$purchases_categories->pluck('id')->flip()->has($expense_category_id)) {
            $category = Category::find($expense_category_id);

            if ($category) {
                $purchases_categories->put($category->id, $category->name);
            }
        }

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        return view('settings.default.edit', compact(
            'accounts',
            'sales_categories',
            'purchases_categories',
            'taxes',
        ));
    }
}
