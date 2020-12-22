<?php

namespace App\Widgets;

use App\Abstracts\Widget;
use App\Models\Banking\Account;

class AccountBalance extends Widget
{
    public $default_name = 'widgets.account_balance';

    public function show()
    {
        $accounts = Account::with('income_transactions', 'expense_transactions')->enabled()->take(5)->get();

        return $this->view('widgets.account_balance', [
            'accounts' => $accounts,
        ]);
    }
}
