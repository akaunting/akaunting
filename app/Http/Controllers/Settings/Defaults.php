<?php

namespace App\Http\Controllers\Settings;

use App\Abstracts\Http\SettingController;
use App\Models\Banking\Account;
use App\Models\Setting\Tax;
use App\Traits\Categories;

class Defaults extends SettingController
{
    use Categories;

    public function edit()
    {
        $accounts = Account::enabled()->orderBy('name')->get()->pluck('title', 'id');

        $taxes = Tax::enabled()->orderBy('name')->get()->pluck('title', 'id');

        // Category listing, remote search and "selected category missing from
        // the initial list" fallback are all handled by x-form.group.category.
        $income_category_types = $this->getIncomeCategoryTypes();
        $expense_category_types = $this->getExpenseCategoryTypes();

        return view('settings.default.edit', compact(
            'accounts',
            'taxes',
            'income_category_types',
            'expense_category_types',
        ));
    }
}
