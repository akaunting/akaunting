<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\Controller;
use App\Models\Banking\Account;
use App\Models\Setting\Category;
use App\Models\Setting\Currency;
use App\Models\Setting\Tax;
use App\Utilities\Modules;

class Defaults extends Controller
{
    public function edit()
    {
        $accounts = Account::enabled()->orderBy('name')->pluck('name', 'id');

        $currencies = Currency::enabled()->orderBy('name')->pluck('name', 'code');

        $sales_categories = Category::income()->enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');

        $sale_category_id = setting('default.income_category');

        if ($sale_category_id && !$sales_categories->has($sale_category_id)) {
            $category = Category::find($sale_category_id);

            if ($category) {
                $sales_categories->put($category->id, $category->name);
            }
        }

        $purchases_categories = Category::expense()->enabled()->orderBy('name')->take(setting('default.select_limit'))->pluck('name', 'id');

        $expense_category_id = setting('default.expense_category');

        if ($expense_category_id && !$purchases_categories->has($expense_category_id)) {
            $category = Category::find($expense_category_id);

            if ($category) {
                $purchases_categories->put($category->id, $category->name);
            }
        }

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $payment_methods = Modules::getPaymentMethods();

        return view('settings.default.edit', compact(
            'accounts',
            'currencies',
            'sales_categories',
            'purchases_categories',
            'taxes',
            'payment_methods'
        ));
    }
}
